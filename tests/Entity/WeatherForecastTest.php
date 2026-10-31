<?php

namespace App\Tests\Entity;

use App\Entity\WeatherForecast;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class WeatherForecastTest extends TestCase
{
    #[DataProvider('dataGetFahrenheit')]
    public function testGetFahrenheit(
        string $celsius,
        float $expectedFahrenheit
    ): void {
        $forecast = new WeatherForecast();

        $forecast->setTemperature($celsius);
        $this->assertSame($expectedFahrenheit, $forecast->getFahrenheit());
    }

    public static function dataGetFahrenheit(): array
    {
        return [
            ['0', 32.0],
            ['-100', -148.0],
            ['100', 212.0],
        ];
    }
}
