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
    public function testTag_3021_PublicNotEnforced()
    {
        $service = new ImageFilterService();
        $service->setEnforcePublicTag(FALSE);

        $service->setTagFilter([3021]);
        $results = $service->run()->getResults();

        self::assertCount(4, $results);
    }

    public function testTag_3021_PublicEnforced()
    {
        $service = new ImageFilterService();
        $service->setEnforcePublicTag(TRUE);

        $service->setTagFilter([3021]);
        $results = $service->run()->getResults();

        self::assertCount(0, $results);
    }

    public function testTag_3022_PublicNotEnforced()
    {
        $service = new ImageFilterService();
        $service->setEnforcePublicTag(FALSE);

        $service->setTagFilter([3022]);
        $results = $service->run()->getResults();

        self::assertCount(1, $results);
    }

    public function testTag_3022_PublicEnforced()
    {
        $service = new ImageFilterService();
        $service->setEnforcePublicTag(TRUE);

        $service->setTagFilter([3022]);
        $results = $service->run()->getResults();

        self::assertCount(1, $results);
    }

    public function testTags_2448_2594_PublicEnforced()
    {
        $service = new ImageFilterService();
        $service->setEnforcePublicTag(TRUE);

        $service->setTagFilter([2448, 2594]);
        $results = $service->run()->getResults();

        self::assertCount(2, $results);
    }

    public function testSingleCameraFilter()
    {
        $service = new ImageFilterService();
        $service->setEnforcePublicTag(TRUE);

        $service->setCameraFilter(["HTC"]);
        $results = $service->run()->getResults();

        self::assertCount(1, $results);
    }

    public function testMultiCameraFilter()
    {
        $service = new ImageFilterService();
        $service->setEnforcePublicTag(TRUE);

        $service->setCameraFilter(["HTC", "FUJIFILM"]);
        $results = $service->run()->getResults();

        self::assertCount(35, $results);
    }

    public function testTagChain()
    {
        $service = new ImageFilterService();
        $service->setEnforcePublicTag(false);

        $service->setTagFilter([2448, 2594]);
        $results = $service->run()->getResults();

        self::assertEquals("Aircraft,Wales,Helicopter", $results[0]["tags"]);
        self::assertEquals("2419,2448,2594", $results[0]["tag_ids"]);
    }
}
