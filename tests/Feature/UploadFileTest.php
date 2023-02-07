<?php

use Illuminate\Http\UploadedFile;

it('should upload a csv file', function () {
    Storage::fake('deliveryTables');
    $uploadFileName = 'customer_1.csv';

    $res = $this->post('/upload', [
        'file' => UploadedFile::fake()->create($uploadFileName, mimeType: 'csv')
    ]);

    $res->assertOk();
    $res->assertJson([
        'jsonrpc' => '2.0',
        'result' => 'ready',
    ]);

    Storage::disk('deliveryTables')->assertExists($uploadFileName);
});
