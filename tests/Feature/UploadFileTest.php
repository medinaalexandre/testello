<?php

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('should upload a csv file', function () {
    $this->freezeTime();

    $customer = Customer::factory()->create();
    $uploadFileName = 'customer_1.csv';

    Storage::fake('deliveryTables');
    Bus::fake();

    $res = $this->post('/upload-csv', [
        'customer_id' => $customer->getKey(),
        'customer_csv' => [
            UploadedFile::fake()->create($uploadFileName, mimeType: 'csv')
        ],
    ]);

    $res->assertRedirect('/');

    Storage::disk('deliveryTables')->assertExists(Carbon::now()->toISOString() . $uploadFileName);
});
