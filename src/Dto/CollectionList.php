<?php
declare(strict_types=1);

namespace App\Dto;

use App\Collection\FruitsCollection;
use App\Collection\VegetablesCollection;

final class CollectionList
{

    private FruitsCollection $fruits;
    private VegetablesCollection $vegetables;

    public function __construct(FruitsCollection $fruits, VegetablesCollection $vegetables) 
    {
        $this->fruits = $fruits; 
        $this->vegetables = $vegetables;
    }

    public function toArray(string $unit = 'g'): array
    {
        return [
            'fruits'      => $this->fruits->listAll($unit),
            'vegetables'  => $this->vegetables->listAll($unit),
        ];
    }
}
