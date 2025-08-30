<?php
declare(strict_types=1);

namespace App\Items;

enum ItemType: string
{
    case FRUIT = 'fruit';
    case VEGETABLE = 'vegetable';

    public static function fromString(string $value): self
    {
        return match (strtolower($value)) {
            'fruit' => self::FRUIT,
            'vegetable' => self::VEGETABLE,
            default => throw new \InvalidArgumentException("Unknown item type: $value - we currently only support fruits and vegetables"),
        };
    }
}
