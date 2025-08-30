<?php
declare(strict_types=1);

namespace App\Collection;

use App\Items\Item;

abstract class AbstractItemCollection implements ItemCollectionInterface
{
    /** @var array<string, Item> key = strtolower(item name) */
    protected array $items = [];

    /** @param iterable<Item> $items */
    public function __construct(iterable $items = [])
    {
        foreach ($items as $item) {
            $this->add($item);
        }
    }

    public function add(Item $item): void
    {
        // Key by lowercase name to avoid duplicates by case
        $key = strtolower($item->name);
        $this->ensureItemType($item);
        $this->items[$key] = $item;
    }

    public function remove(string $name): bool
    {
        $key = strtolower($name);
        if (array_key_exists($key, $this->items)) {
            unset($this->items[$key]);
            return true;
        }
        return false;
    }

    /** @return Item[] */
    public function list(): array
    {
        return array_values($this->items);
    }

    public function listAll(string $unit = 'g'): array
    {
        return array_map(
            static fn(Item $i) => $i->toArray($unit),
            $this->list()
        );
    }

    /** ensure ItemType (fruit/vegetable) */
    abstract protected function ensureItemType(Item $item): void;
}
