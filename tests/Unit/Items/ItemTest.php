<?php
declare(strict_types=1);

namespace App\Tests\Unit\Items;

use App\Items\Item;
use App\Items\ItemType;
use App\Items\Weight;
use PHPUnit\Framework\TestCase;

class ItemTest extends TestCase
{
    public function testCanCreateItem()
    {
        //arrange
        $itemType = ItemType::fromString('vegetable');
        $weight = Weight::fromValues(10922);
        $item = Item::fromValues('Carrot', $itemType , $weight);

        //act

        //assert
        $this->assertEquals('Carrot', $item->name);
        $this->assertEquals('vegetable', $item->type->value);
        $this->assertInstanceOf(Weight::class, $item->weight);
        $this->assertEquals(10922, $item->weight->getWeight());
        $this->assertEquals(10.92, $item->weight->getWeight('kg'));

        $this->assertEquals('Carrot', $item->toArray()['name']);
        $this->assertEquals('vegetable', $item->toArray()['type']);
        $this->assertEquals(10922, $item->toArray()['quantity']);
        $this->assertEquals(10.92, $item->toArray('kg')['quantity']);
        $this->assertEquals('g', $item->toArray()['unit']);
        $this->assertEquals('kg', $item->toArray('kg')['unit']);
    }


    public function testCanCreateItemFromG()
    {
        //arrange
        $itemType = ItemType::fromString('fruit');
        $weight = Weight::fromValues(3500, 'g');
        $item = Item::fromValues('Pears', $itemType , $weight);
        
        //act
        
        //assert
        $this->assertEquals('fruit', $item->type->value);
        $this->assertEquals(3500, $item->weight->getWeight());
        $this->assertEquals(3.5, $item->weight->getWeight('kg'));
    }

    public function testCanCreateItemFromKg()
    {
        //arrange
        $itemType = ItemType::fromString('fruit');
        $weight = Weight::fromValues(20, 'kg');
        $item = Item::fromValues('Apples', $itemType , $weight);

        //act
        
        //assert
        $this->assertEquals(20000, $item->weight->getWeight());
        $this->assertEquals(20, $item->weight->getWeight('kg'));
    }
}
