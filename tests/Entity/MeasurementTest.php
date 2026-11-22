<?php

namespace App\Tests\Entity;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\DataProvider;

class MeasurementTest extends TestCase
{
    #[DataProvider('dataGetFahrenheit')]
    public function testGetFahrenheit(string $celsius, string $expectedFahrenheit): void
    {
        $measurement = new \App\Entity\Mesurement();

        $measurement->setCelsius($celsius);
        $this->assertSame($expectedFahrenheit, $measurement->getFahrenheit());
    }

    public static function dataGetFahrenheit(): array
    {
        return [
            ['0', '32'],
            ['-100', '-148'],
            ['100', '212'],
            ['0.5', '32.9'],
            ['-0.5', '31.1'],
            ['1', '33.8'],
            ['-1', '30.2'],
            ['20', '68'],
            ['37', '98.6'],
            ['-40', '-40'],
        ];
    }
}
