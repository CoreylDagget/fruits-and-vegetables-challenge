<?php
declare(strict_types=1);

namespace App\Service;

use App\Collection\FruitsCollection;
use App\Collection\VegetablesCollection;
use App\Dto\CollectionList;
use App\Items\Item;
use App\Items\ItemType;
use App\Items\Weight;
use InvalidArgumentException;
use Symfony\Component\HttpFoundation\Request;

class RequestJsonProcessor
{
    private CollectionListFileStore $store;

    public function __construct(CollectionListFileStore $store)
    {
        $this->store = $store;
    }

    public function processRequest(Request $request): CollectionList
    {
        $json = $request->getContent();
        $collectionList = $this->processJson($json);
        $this->saveCollectionList($collectionList);

        return $collectionList;
    }

    public function processJson(string $json): CollectionList
    {
        $data = json_decode($json, true, 512, JSON_THROW_ON_ERROR);

        if (!is_array($data)) {
            throw new InvalidArgumentException('invalid payload: array expected');
        }

        $fruits = new FruitsCollection();
        $vegetables = new VegetablesCollection();

        foreach ($data as $row) {
            $this->verifyRow($row);

            $type = ItemType::fromString((string)$row['type']);
            $weight = Weight::fromValues(
                $row['quantity'],
                isset($row['unit']) ? (string)$row['unit'] : 'g'
            );

            $item = Item::fromValues((string)$row['name'], $type, $weight);

            if ($type->value === 'fruit') {
                $fruits->add($item);
            } elseif ($type->value === 'vegetable') {
                $vegetables->add($item);
            }
        }

        return new CollectionList($fruits, $vegetables);
    }

    private function verifyRow($row): void
    {
        if (!is_array($row)) {
            throw new InvalidArgumentException('invalid row: array expected');
        }

        foreach (['name','type','quantity'] as $key) {
            if (!array_key_exists($key, $row)) {
                throw new InvalidArgumentException(sprintf('missing required field "%s"', $key));
            }
        }
    }

    private function saveCollectionList(CollectionList $collectionList): void
    {
        $this->store->save($collectionList);
    }
}
