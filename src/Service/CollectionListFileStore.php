<?php
declare(strict_types=1);

namespace App\Service;

use App\Collection\FruitsCollection;
use App\Collection\VegetablesCollection;
use App\Dto\CollectionList;
use App\Items\Item;
use App\Items\ItemType;
use App\Items\Weight;
use RuntimeException;
use Symfony\Component\Filesystem\Filesystem;

class CollectionListFileStore
{
    private Filesystem $fs;
    private string $filePath;

    public function __construct(Filesystem $fs, string $filePath)
    {
        $this->fs = $fs;
        $this->filePath = $filePath;
    }

    public function save(CollectionList $collections): void
    {
        $serialized = serialize($collections);
        $json = json_encode($serialized, JSON_THROW_ON_ERROR);
        $this->atomicWrite($json);
    }

    public function load(): CollectionList
    {
        if (!$this->fs->exists($this->filePath)) {
            throw new RuntimeException('no stored List available.');
        }

        $json = @file_get_contents($this->filePath);
        if ($json === false) {
            throw new RuntimeException('failed to read storage file.');
        }

        $serialized = json_decode($json, true, 512, JSON_THROW_ON_ERROR);
        if (!is_string($serialized) || $serialized === '') {
            throw new RuntimeException('corrupted storage payload.');
        }

        $obj = @unserialize($serialized, ['allowed_classes' => $this->allowedClasses()]);
        if (!$obj instanceof CollectionList) {
            throw new RuntimeException('unexpected type after unserialize.');
        }

        return $obj;
    }

    public function clear(): void
    {
        if ($this->fs->exists($this->filePath)) {
            $this->fs->remove($this->filePath);
        }
    }

    /** @return list<class-string> */
    private function allowedClasses(): array
    {
        return [
            CollectionList::class,
            FruitsCollection::class,
            VegetablesCollection::class,
            Item::class,
            ItemType::class,
            Weight::class,
        ];
    }

    private function atomicWrite(string $contents): void
    {
        $dir = \dirname($this->filePath);
        if (!$this->fs->exists($dir)) {
            $this->fs->mkdir($dir, 0770);
        }

        $tmp = $dir . DIRECTORY_SEPARATOR . '.groceries.' . bin2hex(random_bytes(8)) . '.tmp';

        $fh = @fopen($tmp, 'wb');
        if ($fh === false) {
            throw new RuntimeException('failed to create temp file for write.');
        }

        try {
            if (!flock($fh, LOCK_EX)) {
                throw new RuntimeException('failed to acquire file lock.');
            }

            $written = fwrite($fh, $contents);
            if ($written === false || $written !== strlen($contents)) {
                throw new RuntimeException('failed to write all bytes.');
            }

            fflush($fh);
            @chmod($tmp, 0600);
        } finally {
            flock($fh, LOCK_UN);
            fclose($fh);
        }

        if (!@rename($tmp, $this->filePath)) {
            @unlink($tmp);
            throw new RuntimeException('failed to move file into place.');
        }
    }
}
