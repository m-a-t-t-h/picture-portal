<?php namespace Integration;

use App\Services\ImageFilterService;
use Tests\TestCase;

/**
 * @bug  These tests are brittle and rely on a specific set of tagged images in my reference database. They won't work for you.
 * @todo Decouple the reference database from the unit tests.
 *
 * If tests randomly fail, trace back the tag chain to ensure I've not updated anything in DigiKam that breaks the tags.
 */
class ImageFilterResultsTest extends TestCase
{
    public function testTag_3021_PublicNotEnforced()
    {
        $service = new ImageFilterService();
        $service->setEnforcePublicTag(FALSE);

        $service->setTagFilter([3021]);
        $results = $service->run()->getResults();

        self::assertCount(4, $results);

        self::assertNotNull($results[0]["img_id"]);
        self::assertEquals("Cars,Qashqai", $results[0]["tags"]);
    }


}
