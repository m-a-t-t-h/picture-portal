<?php namespace Integration\Models;

use App\Http\Api\FilterController;
use App\Models\Images;
use App\Models\ImageTags;
use App\Models\TagExcluded;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class ImageRelationshipsTest extends TestCase
{
    public function testTags()
    {
        $image = Images::where("id", 74960)->first();

        $this->assertTrue(collect($image->imageTags)->contains("name", "Color Label Green"));
        $this->assertTrue(collect($image->imageTags)->contains("name", "Pick Label None"));
        $this->assertTrue(collect($image->imageTags)->contains("name", "Lake District"));
        $this->assertTrue(collect($image->imageTags)->contains("name", "Nether Wasdale campsite"));
    }

    public function testImageInformation() {
        $image = Images::where("id", 74960)->first();
        $ii = $image->imageInformation;

        self::assertEquals(2896, $ii->width);
        self::assertEquals(1944, $ii->height);
    }

    public function testAlbum() {
        $image = Images::where("id", 74960)->first();
        $al = $image->imageAlbum;

        self::assertEquals(5, $al->albumRoot);
        self::assertEquals("2006-01-01", $al->date);
    }
}
