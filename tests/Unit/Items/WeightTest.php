<?php
declare(strict_types=1);

namespace App\Tests\Unit\Items;

use App\Items\Weight;
use PHPUnit\Framework\TestCase;

final class WeightTest extends TestCase
{
    public function testFromValuesWithGrams(): void
    {
        $w = Weight::fromValues(750, 'g');

        $this->assertSame(750, $w->getWeight('g'));
        $this->assertSame(0.75, $w->getWeight('kg'));
    }

    public function testFromValuesWithKg(): void
    {
        $w = Weight::fromValues(2, 'kg');

        $this->assertSame(2000, $w->getWeight('g'));
        $this->assertSame(2.0, $w->getWeight('kg'));
    }

    public function testGetWeightRoundingInKg(): void
    {
        $w = Weight::fromValues(1555, 'g');
        $this->assertSame(1.56, $w->getWeight('kg'));
    }

    /**
     * @dataProvider provideNonPositiveQuantities
     */
    public function testFromValuesRejectsNonPositiveQuantity(int $qty): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('weight/quantity must be above 0');

        Weight::fromValues($qty, 'g');
    }

    public static function provideNonPositiveQuantities(): array
    {
        return [
            [0],
            [-42],
        ];
    }

    /**
     * @dataProvider provideInvalidUnits
     */
    public function testFromValuesRejectsInvalidUnit(string $unit): void
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage('weigth must be a metric value "g" as in grams or "kg" as in kilograms');

        Weight::fromValues(100, $unit);
    }

    public static function provideInvalidUnits(): array
    {
        return [
            ['lb'],
            ['oz'],
        ];
    }
}
