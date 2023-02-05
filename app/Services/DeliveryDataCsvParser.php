<?php

namespace App\Services;

class DeliveryDataCsvParser extends DeliveryDataParser
{
    public static function fromCsvLine(array $attributes): self
    {
        return (new static(...$attributes));
    }

    public function getNormalizedFromPostcode(): string
    {
        return preg_replace('/\D/', '', $this->fromPostcode);
    }

    public function getNormalizedToPostcode(): string
    {
        return preg_replace('/\D/', '', $this->toPostcode);
    }

    public function getNormalizedFromWeight(): float
    {
        return (float) str_replace(',', '.', $this->fromWeight);
    }

    public function getNormalizedToWeight(): float
    {
        return (float) str_replace(',', '.', $this->fromWeight);
    }

    public function getCostCents(): int
    {
        return (int) ((float) str_replace(',', '.', $this->cost) * 100);
    }
}
