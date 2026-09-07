<?php namespace Integration;

use App\Services\ImageFilterService;
use Tests\TestCase;

/**
 * @bug  These tests are brittle and rely on a specific set of tagged images in my reference database. They won't work for you.
 * @todo Decouple the reference database from the unit tests.
 *
 * If tests randomly fail, trace back the tag chain to ensure I've not updated anything in DigiKam that breaks the tags.
 */
class ImageFilterTest extends TestCase
{
    private ImageFilterService $service;

    public function testTag_3021_PublicNotEnforced()
    {
        $results = $this->service
            ->setEnforcePublicTag(FALSE)
            ->setTagFilter([3021])
            ->buildQuery()->runQuery()->getResults();

        self::assertNotNull($results);
        self::assertCount(4, $results);
    }

    public function testTag_3021_PublicEnforced()
    {
        $results = $this->service
            ->setEnforcePublicTag(TRUE)
            ->setTagFilter([3021])
            ->buildQuery()->runQuery()->getResults();

        self::assertNotNull($results);
        self::assertCount(0, $results);
    }

    public function testTag_3022_PublicNotEnforced()
    {
        $results = $this->service
            ->setEnforcePublicTag(FALSE)
            ->setTagFilter([3022])
            ->buildQuery()->runQuery()->getResults();

        self::assertNotNull($results);
        self::assertCount(2, $results);
    }

    public function testTag_3022_PublicEnforced()
    {
        $results = $this->service
            ->setEnforcePublicTag(TRUE)
            ->setTagFilter([3022])
            ->buildQuery()->runQuery()->getResults();

        self::assertNotNull($results);
        self::assertCount(2, $results);
    }

    public function testTags_2448_2594_PublicEnforced()
    {
        $results = $this->service
            ->setEnforcePublicTag(TRUE)
            ->setTagFilter([2448, 2594])
            ->buildQuery()->runQuery()->getResults();

        self::assertNotNull($results);
        self::assertCount(0, $results);
    }

    public function testSingleCameraFilter()
    {
        $results = $this->service
            ->setEnforcePublicTag(TRUE)
            ->setCameraFilter(["HTC"])
            ->buildQuery()->runQuery()->getResults();

        self::assertNotNull($results);
        self::assertCount(1, $results);
    }

    public function testSingleCameraFilter2()
    {
        $results = $this->service
            ->setEnforcePublicTag(TRUE)
            ->setCameraFilter(["FUJIFILM"])
            ->setPageSize(200)
            ->buildQuery()->runQuery()->getResults();

        self::assertNotNull($results);
        self::assertCount(32, $results);
    }

    public function testMultiCameraFilter()
    {
        $results = $this->service
            ->setEnforcePublicTag(TRUE)
            ->setCameraFilter(["HTC", "FUJIFILM"])
            ->setPageSize(200)
            ->buildQuery()->runQuery()->getResults();

        self::assertNotNull($results);
        self::assertCount(33, $results);
    }

    public function testTagChain()
    {
        $results = $this->service
            ->setEnforcePublicTag(FALSE)
            ->setTagFilter([2448, 2594])
            ->buildQuery()->runQuery()->getResults();

        self::assertNotNull($results);
        self::assertEquals("Aircraft,Wales,Helicopter", implode(",", array_column($results[0]["tags"], 1)));
        self::assertEquals("2419,2448,2594",implode(",", array_column($results[0]["tags"], 0)));
    }

    public function setUp(): void
    {
        parent::setUp();

        $this->service = (new ImageFilterService())->setCollectionId(5);

    }
}
