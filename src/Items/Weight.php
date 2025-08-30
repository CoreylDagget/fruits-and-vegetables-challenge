<?php
declare(strict_types=1);

namespace App\Items;

class Weight
{
    private int $quantity; // in g
    private string $unit;

    private function __construct(int $quantity, string $unit = 'g')
    {
        $this->quantity = $quantity;
        $this->unit = $unit;
    }

    public static function fromValues(int $quantity, string $unit = 'g'): self
    {
        if($quantity <= 0) {
            throw new \Exception('weight/quantity must be above 0');
        }

        $unit = strtolower($unit);

        if($unit !== 'g' && $unit !== 'kg'){
            throw new \Exception('weigth must be a metric value "g" as in grams or "kg" as in kilograms, '. $unit .' was given');
        }

        if($unit === 'kg'){
            $quantity = $quantity * 1000;
        }

        return new self($quantity, $unit);
    }

    public function getWeight(string $unit = 'g') {
        if($unit === 'kg')
        {
            return round($this->quantity / 1000, 2);
        }

        return $this->quantity;
    }
}