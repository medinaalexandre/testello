<?php

namespace App\Jobs;

use App\Models\Customer;
use App\Models\Delivery\DeliveryLocation;
use App\Models\Delivery\DeliveryWeightCost;
use App\Services\DeliveryDataCsvParser;
use Carbon\Carbon;
use Closure;
use DB;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;
use JsonException;
use Log;
use RuntimeException;
use Throwable;

class ProcessCustomerDeliveryCsv implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private array $validHeader = [
        'from_postcode',
        'to_postcode',
        'from_weight',
        'to_weight',
        'cost'
    ];

    protected array $unprocessedLines = [];

    public function __construct(
        protected int $customerId,
        protected string $fileName
    ) {

    }

    /**
     * @throws Throwable
     * @throws JsonException
     */
    public function handle(): void
    {
        $filePath = Storage::disk('deliveryTables')->path($this->fileName);

        if (!$file = fopen($filePath, 'rb')) {
            throw new RuntimeException("Failed to open file $filePath");
        }

        try {
            $this->validateHeader($file);

            $deliveries = [];
            $nonProcessedLines = [];

            while ($line = fgetcsv($file, separator: ';')) {
                try {
                    $deliveryData = DeliveryDataCsvParser::fromCsvLine($line);
                    $deliveries[$deliveryData->fromPostcode][$deliveryData->toPostcode][] = $deliveryData;
                } catch (Throwable) {
                    $this->unprocessedLines[] = json_encode($line, JSON_THROW_ON_ERROR);
                }
            }

            DB::transaction($this->insertDataToDatabase($deliveries));

            if (!empty($this->unprocessedLines)) {
                Log::warning("Some lines of $this->fileName weren't imported", ['lines' => $nonProcessedLines]);
            }

        } finally {
            fclose($file);
        }
    }

    protected function insertDataToDatabase(array $deliveries): Closure
    {
        return function () use ($deliveries) {
            foreach ($deliveries as $destinations) {
                foreach ($destinations as $costs) {
                    /** @var DeliveryDataCsvParser $deliveryData */
                    $deliveryData = $costs[0];

                    if (!$deliveryData->hasValidLocation()) {
                        $this->unprocessedLines[] = $deliveryData->toString();
                        continue;
                    }

                    $location = new DeliveryLocation();
                    $location->from_postcode = $deliveryData->getNormalizedFromPostcode();
                    $location->to_postcode = $deliveryData->getNormalizedToPostcode();
                    $location->customer_id = $this->customerId;
                    $location->save();

                    /** @var DeliveryDataCsvParser $cost */
                    foreach ($costs as $cost) {
                        if (!$cost->isValid()) {
                            $this->unprocessedLines[] = $cost->toString();
                            continue;
                        }

                        $weightCost = new DeliveryWeightCost();
                        $weightCost->location_id = $location->getKey();
                        $weightCost->from_weight = $cost->getNormalizedFromWeight();
                        $weightCost->to_weight = $cost->getNormalizedToWeight();
                        $weightCost->cost_cents = $cost->getCostCents();
                        $weightCost->save();
                    }
                }
            }

            $customer = Customer::find($this->customerId);
            $customer->last_readjustment_at = Carbon::now();
            $customer->save();
        };
    }

    /**
     * @throws JsonException
     */
    public function validateHeader($file): void
    {
        $header = fgetcsv($file, separator: ';');

        if ($header !== $this->validHeader) {
            throw new RuntimeException(
                sprintf(
                    "The header of file %s isn't correct\n
                        Expected: %s\n
                        Actual: %s\n",
                    $this->fileName,
                    json_encode($header, JSON_THROW_ON_ERROR),
                    json_encode($this->fileName, JSON_THROW_ON_ERROR),
                ));
        }
    }
}
