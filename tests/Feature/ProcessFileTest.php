<?php

use App\Jobs\ProcessCustomerDeliveryCsv;
use App\Models\Customer;
use App\Models\Delivery\DeliveryLocation;
use App\Models\Delivery\DeliveryWeightCost;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

beforeEach(function () {
    Storage::fake('deliveryTables');
    $this->customer =  Customer::factory()->create();
    $this->fileName = 'deliveries_price.csv';
});

it('should fail if the header is invalid', function () {
    $file = UploadedFile::fake()
        ->createWithContent($this->fileName, 'from_postcode;t;from_weight;to_weight;');

    Storage::disk('deliveryTables')->putFileAs('', $file, $this->fileName);
    Storage::disk('deliveryTables')->assertExists($this->fileName);

    dispatch(new ProcessCustomerDeliveryCsv($this->customer->getKey(), $this->fileName));
})->throws(RuntimeException::class);

it('should log the line if a field is invalid: ', function ($content) {
    $header = "from_postcode;to_postcode;from_weight;to_weight;cost\n";
    $content = $header . $content;
    $file = UploadedFile::fake()->createWithContent($this->fileName, $content);

    Storage::disk('deliveryTables')->putFileAs('', $file, $this->fileName);
    Storage::disk('deliveryTables')->assertExists($this->fileName);

    Log::shouldReceive('warning')
        ->once();

    dispatch(new ProcessCustomerDeliveryCsv($this->customer->getKey(), $this->fileName));
})->with([
    'invalid from_postcode' => '01.400-00022;01.499-999;0,00;0,25;5,20',
    'invalid to_postcode' => '01.400-000;499-999;0,00;0,25;5,20',
    'invalid from_weight' => '01.400-000;01.499-999;0-00;0,25;5,20',
    'invalid to_weight' => '01.400-000;01.499-999;0,00;invalid#@;5,20',
    'invalid_cost' => '01.400-000;01.499-999;0,00;0,25;#',
]);

it('should import all the csv data if the content is valid', function () {
    $file = UploadedFile::fake()->createWithContent(
        $this->fileName,
        <<<CONTENT
        from_postcode;to_postcode;from_weight;to_weight;cost
        01.400-000;01.499-999;0,00;0,25;5,20
        02.000-000;02.099-999;0,00;0,25;5,20
        01.400-000;01.499-999;0,25;0,50;5,97
        CONTENT
    );

    Storage::disk('deliveryTables')->putFileAs('', $file, $this->fileName);
    Storage::disk('deliveryTables')->assertExists($this->fileName);

    dispatch(new ProcessCustomerDeliveryCsv($this->customer->getKey(), $this->fileName));

    $locations = DeliveryLocation::query()
        ->where('customer_id', '=', $this->customer->getKey())
        ->get();
    $prices = DeliveryWeightCost::query()
        ->whereIn('location_id', $locations->pluck('location_id'))
        ->get();

    expect($locations->count())->toBe(2)
        ->and($prices->count())->toBe(3);
});

it('should store the normalized data from the csv', function () {
    $file = UploadedFile::fake()->createWithContent(
        $this->fileName,
        <<<CONTENT
        from_postcode;to_postcode;from_weight;to_weight;cost
        01.400-000;01.499-999;0,00;0,25;5,20
        01.400-000;01.499-999;0,25;0,50;5,97
        CONTENT
    );

    Storage::disk('deliveryTables')->putFileAs('', $file, $this->fileName);
    Storage::disk('deliveryTables')->assertExists($this->fileName);

    dispatch(new ProcessCustomerDeliveryCsv($this->customer->getKey(), $this->fileName));

    $locations = DeliveryLocation::query()
        ->where('customer_id', '=', $this->customer->getKey())
        ->where('from_postcode', '=', '01400000')
        ->where('to_postcode', '=', '01499999')
        ->firstOrFail();

    $weightCosts = $locations->weightCosts;

    expect($weightCosts->count())->ToBe(2)
        ->and($weightCosts[0]->from_weight)->toBe(0.0)
        ->and($weightCosts[0]->to_weight)->toBe(0.25)
        ->and($weightCosts[0]->cost_cents)->toBe(520)
        ->and($weightCosts[1]->from_weight)->toBe(0.25)
        ->and($weightCosts[1]->to_weight)->toBe(0.50)
        ->and($weightCosts[1]->cost_cents)->toBe(597);
});
