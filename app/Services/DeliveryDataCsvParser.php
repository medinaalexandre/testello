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

    public function getNormalizedFromWeight(): ?float
    {
        $normalized =  str_replace(',', '.', $this->fromWeight);

        if (!is_numeric($normalized)) {
            return null;
        }

        return (float) $normalized;
    }

    public function getNormalizedToWeight(): ?float
    {
        $normalized =  str_replace(',', '.', $this->toWeight);

        if (!is_numeric($normalized)) {
            return null;
        }

        return (float) $normalized;
    }

    public function getCostCents(): ?int
    {
        $normalized = str_replace(',', '.', $this->cost);

        if (!is_numeric($normalized)) {
            return null;
        }

        return (int) ((float) str_replace(',', '.', $this->cost) * 100);
    }
}
