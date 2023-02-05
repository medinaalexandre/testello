<?php

use App\Models\Customer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('should upload a csv file', function () {
    $customer = Customer::factory()->create();
    $uploadFileName = 'customer_1.csv';

    Storage::fake('deliveryTables');

    $res = $this->post('/upload-csv', [
        'customer_id' => $customer->getKey(),
        'customer_csv' => UploadedFile::fake()->create($uploadFileName, mimeType: 'csv'),
    ]);

    $res->assertRedirect('/');

    Storage::disk('deliveryTables')->assertExists($uploadFileName);
});
