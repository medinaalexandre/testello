<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadCsvRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customer,customer_id',
            'customer_csv' => 'required|file|mimetypes:csv',
        ];
    }
}
