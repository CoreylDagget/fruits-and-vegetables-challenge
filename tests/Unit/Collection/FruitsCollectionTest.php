<?php
declare(strict_types=1);

namespace App\Tests\Unit\Collection;

use App\Collection\FruitsCollection;
use App\Items\Item;
use App\Items\ItemType;
use App\Items\Weight;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;

class FruitsCollectionTest extends TestCase
{
    public function testCanFruitCollectionReturnItems(): void
    {
        //arrange
        $item = $this->createItem('Apple', 'fruit');
        $fruitsCollection = new FruitsCollection([$item]);

        //act
        $fruits = $fruitsCollection->list();

        //assert
        self::assertCount(1, $fruits);
        self::assertSame($item, $fruits[0]);
    }

    public function testCanRemoveByNameFromFruitCollection(): void
    {
        //arrange
        $item = $this->createItem('Banana', 'fruit');
        $fruitsCollection = new FruitsCollection([$item]);

        //act
        $removed = $fruitsCollection->remove('banana');
        $notRemoved = $fruitsCollection->remove('banana');

        //assert
        self::assertTrue($removed);
        self::assertFalse($notRemoved);
        self::assertCount(0, $fruitsCollection->list());
    }

    public function testAddNonFruitThrows(): void
    {
        $item = $this->createItem('Carrot', 'vegetable');
        $fruitsCollection = new FruitsCollection();

        $this->expectException(InvalidArgumentException::class);
        $this->expectExceptionMessage('FruitsCollection only accepts Items of type fruit');

        $fruitsCollection->add($item);
    }

    private function createItem(string $name, string $typeValue): Item
    {
        $itemType = ItemType::fromString($typeValue);
        $weight = Weight::fromValues(313373);

        return Item::fromValues($name, $itemType , $weight);
    }
}
