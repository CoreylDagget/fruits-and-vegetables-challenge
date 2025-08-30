<?php
declare(strict_types=1);

namespace App\Tests\Unit\Items;

use App\Items\ItemType;
use PHPUnit\Framework\TestCase;

final class ItemTypeTest extends TestCase
{
    /**
     * @dataProvider provideValidValues
     */
    public function testFromStringReturnsExpectedEnum(string $input, ItemType $expected): void
    {
        $this->assertSame($expected, ItemType::fromString($input));
    }

    public function provideValidValues(): array
    {
        return [
            ['fruit', ItemType::FRUIT],
            ['FRUIT', ItemType::FRUIT],
            ['vegetable', ItemType::VEGETABLE],
            ['VEGETABLE', ItemType::VEGETABLE],
        ];
    }

    public function testFromStringThrowsExceptionForUnknownType(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage(
            'Unknown item type: meat - we currently only support fruits and vegetables'
        );

        ItemType::fromString('meat');
    }
}
