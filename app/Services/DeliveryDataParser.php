<?php

namespace App\Services;

abstract class DeliveryDataParser
{
    public function __construct(
        public readonly string $fromPostcode,
        public readonly string $toPostcode,
        public readonly string $fromWeight,
        public readonly string $toWeight,
        public readonly string $cost,
    ) {
    }

    abstract public function getNormalizedFromPostcode(): string;
    abstract public function getNormalizedToPostcode(): string;
    abstract public function getNormalizedFromWeight(): float;
    abstract public function getNormalizedToWeight(): float;
    abstract public function getCostCents(): int;

    public function toString(): string
    {
        return sprintf(
            '%s;%s;%s;%s;%s',
            $this->fromPostcode,
            $this->toPostcode,
            $this->fromWeight,
            $this->toWeight,
            $this->cost,
        );
    }

    public function isValid(): bool
    {
        return $this->isValidPostCode($this->getNormalizedToPostcode())
            && $this->isValidPostCode($this->getNormalizedFromPostcode())
            && is_numeric($this->getNormalizedFromWeight())
            && is_numeric($this->getNormalizedToWeight())
            && is_numeric($this->getCostCents());
    }

    protected function isValidPostCode(string $postcode): bool
    {
        return strlen($postcode) === 8;
    }
}
