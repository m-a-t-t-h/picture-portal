<?php namespace Integration;

use App\Services\ImageFilterService;
use App\Services\ImgSrv;
use Tests\TestCase;

/**
 * @bug  These tests are brittle and rely on a specific set of tagged images in my reference database. They won't work for you.
 * @todo Decouple the reference database from the unit tests.
 *
 * If tests randomly fail, trace back the tag chain to ensure I've not updated anything in DigiKam that breaks the tags.
 */
class ImgSrvTest extends TestCase
{
   public function testBug01() {

       $hash = "2376e05a7b442a4ecd846620089d5a094ea4870a01be24543330c8ee65f9c616";
       $path = ImgSrv::hashToPath($hash, image_url_prefix_strip: "/Photos");

       self::assertEquals("col5/Photos/Portfolio/DSCF2257_tonemapped.png", $path);
   }
}
