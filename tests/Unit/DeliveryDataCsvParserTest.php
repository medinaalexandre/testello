<?php

use App\Services\DeliveryDataCsvParser;

it('should correct parse the float values', function (string $valueToNormalize, float $expected) {
    $parser = new DeliveryDataCsvParser('01.400-000', '01.400-999', $valueToNormalize, $valueToNormalize, '5,20');

    expect($parser->getNormalizedFromWeight())->toBe($expected)
        ->and($parser->getNormalizedToWeight())->toBe($expected);
})->with([
    ['1,00', 1.00],
    ['9.999,02', 9999.02],
    ['0,25', 0.25],
]);

it('should convert cost to cost cents', function (string $value, int $expected) {
    $parser = new DeliveryDataCsvParser('01.400-000', '01.400-999', '0,00', '0,25', $value);
    expect($parser->getCostCents())->toBe($expected);
})->with([
    ['5,20', 520],
    ['9.500,20', 950020]
]);
