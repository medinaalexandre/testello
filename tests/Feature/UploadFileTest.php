<?php

use App\Models\Customer;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

it('should upload a csv file', function () {
    $customer = Customer::factory()->create();
    $uploadFileName = 'customer_1.csv';

    Storage::fake();

    $res = $this->post('/upload-csv', [
        'customer_id' => $customer->getKey(),
        'customer_csv' => UploadedFile::fake()->create($uploadFileName, mimeType: 'csv'),
    ]);

    $res->assertRedirect('/');

    Storage::disk()->assertExists('delivery-tables/' . $uploadFileName);
});

it('should fail the upload if the mimetype isn\'t csv', function () {
    $customer = Customer::factory()->create();
    $uploadFileName = 'customer_1.csv';

    $res = $this->post('/upload-csv', [
        'customer_id' => $customer->getKey(),
        'customer_csv' => UploadedFile::fake()->create($uploadFileName, mimeType: 'png'),
    ]);

    $res->assertSessionHasErrors(['customer_csv']);
});
