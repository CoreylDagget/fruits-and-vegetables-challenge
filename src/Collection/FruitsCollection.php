<?php
declare(strict_types=1);

namespace App\Collection;

use App\Items\Item;
use InvalidArgumentException;

class FruitsCollection extends AbstractItemCollection
{
    protected function ensureItemType(Item $item): void
    {
        if ($item->type->value !== 'fruit') {
            throw new InvalidArgumentException('FruitsCollection only accepts Items of type fruit');
        }
    }
}
