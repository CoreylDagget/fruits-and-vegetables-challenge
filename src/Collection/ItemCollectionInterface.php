<?php
declare(strict_types=1);

namespace App\Collection;

use App\Items\Item;

interface ItemCollectionInterface
{
    public function add(Item $item): void;

    /**
     * remove item by name
     * @return bool true, when found and removed
     */
    public function remove(string $name): bool;

    /** 
     * @return Item[] 
     */
    public function list(): array;

    /**
     * use Item::toArray().
     * @return array<int, array{name:string,type:string,quantity:int|float,unit:string}>
     */
    public function listAll(string $unit = 'g'): array;
}
