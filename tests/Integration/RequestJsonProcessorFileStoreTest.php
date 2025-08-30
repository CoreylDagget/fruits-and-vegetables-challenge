<?php
declare(strict_types=1);

namespace App\Tests\Integration;

use App\Service\CollectionListFileStore;
use App\Service\RequestJsonProcessor;
use App\Dto\CollectionList;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;

class RequestJsonProcessorFileStoreTest extends TestCase
{
    private Filesystem $fs;
    private string $tmpFile;
    private string $requestJsonPath;

    protected function setUp(): void
    {
        parent::setUp();
        $this->fs = new Filesystem();
        $this->tmpFile = __DIR__ . DIRECTORY_SEPARATOR . '../../collectionlist_serialized.dat';
        $this->requestJsonPath =  __DIR__ . DIRECTORY_SEPARATOR . '../../request.json';

        if ($this->fs->exists($this->tmpFile)) {
            $this->fs->remove($this->tmpFile);
        }
    }

    protected function tearDown(): void
    {
        if (isset($this->fs) && $this->fs->exists($this->tmpFile)) {
           //comment the remove for the *.dat file to stay
           $this->fs->remove($this->tmpFile);
        }
        parent::tearDown();
    }

    public function testCanProcessRequestJsonAndLoadPersistedData(): void
    {
        //arrange
        $collectionListFileStore = new CollectionListFileStore($this->fs, $this->tmpFile);
        $requestJsonProcessor = new RequestJsonProcessor($collectionListFileStore);
        $json = null;
        
        // load request.json, create Request
        if ($this->fs->exists($this->requestJsonPath)) {
            $json = @file_get_contents($this->requestJsonPath);
            if ($json === false) {
                throw new RuntimeException('failed to read request.json file.');
            }
        }
        $request = new Request(content: $json);

        //act

        //$result is a CollectionList with a Fruits and VegetablCollection
        $result = $requestJsonProcessor->processRequest($request);

        //uncomment the var_dump to see the CollectionList in console output during testruns
        #var_dump($result);

        //assert

        //asserts if the *.dat file with the CollectionList exists
        self::assertFileExists($this->tmpFile);
        self::assertGreaterThan(0, filesize($this->tmpFile));

        //loads CollectionList from store, asserts that stored and processed are equal
        $collectionList = $collectionListFileStore->load();

        $this->assertInstanceOf(CollectionList::class, $collectionList);
        $this->assertEquals($result, $collectionList);
    }
}
