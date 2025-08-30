<?php
declare(strict_types=1);

namespace App\Items;

class Item
{
    public readonly string $name;
    public readonly ItemType $type;
    public readonly Weight $weight;

    private function __construct(string $name, ItemType $type, Weight $weight)
    {
        $this->name = $name;
        $this->type = $type;
        $this->weight = $weight;
    }

    public static function fromValues(string $name, ItemType $type, Weight $weight) {
        return new self($name, $type, $weight);
    }

    public function toArray(string $unit = 'g'): array
    {
        return [
            'name' => $this->name,
            'type' => $this->type->value,
            'quantity' => $this->weight->getWeight($unit),
            'unit' => $unit,
        ];
    }
}
