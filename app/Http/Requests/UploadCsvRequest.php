<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\UploadedFile;

class UploadCsvRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customer,customer_id',
            'customer_csv' => 'required|file',
        ];
    }

    public function getCustomerId(): int
    {
        return $this->validated()['customer_id'];
    }

    public function getCustomerCsv(): array|UploadedFile|null
    {
        return $this->file('customer_csv');
    }
}
