<?php
declare(strict_types=1);

namespace App\Collection;

use App\Items\Item;
use InvalidArgumentException;

class VegetablesCollection extends AbstractItemCollection
{
    protected function ensureItemType(Item $item): void
    {
        if ($item->type->value !== 'vegetable') {
            throw new InvalidArgumentException('VegetablesCollection only accepts Items of type fruit');
        }
    }
}
