<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProcessFilesRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'customer_id' => 'required|exists:customer,customer_id',
            'filenames' => 'required|array',
            'filenames.*' => 'required|string',
        ];
    }

    public function getCustomerId(): int
    {
        return $this->validated()['customer_id'];
    }

    public function getFilenames(): array
    {
        return $this->validated()['filenames'];
    }
}
