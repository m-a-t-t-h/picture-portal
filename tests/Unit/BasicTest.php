<?php

namespace Tests\Unit;

use App\Models\Albums;
use App\Models\Images;
use App\Models\Tags;
use Tests\TestCase;

/**
 * @todo
 */
class BasicTest extends TestCase
{
    /**
     * A basic test example.
     */
    public function test_that_true_is_true(): void
    {
        $this->assertTrue(TRUE);

        $albums = Albums::get();
        dd($albums)->toArray();
    }

    public function testTags()
    {
        $tags = Tags::toplevel()->orderBy("name")->get();
        dd($tags[0]->toArray());
    }

    public function testTagMatches()
    {
        $matchs = Tags::match("XH558");
        foreach ($matchs as $m) {
            print "[" . $m->name . "]\n";
        }
    }

    public function testCreateTree()
    {
        $tree = Tags::createTreeDownwards();
        dd($tree);
    }

    public function testAlbumRelationship()
    {

        $images = Images::first();
        dd($images->path);

        dd($images->album->toArray());
    }

    public function tesTagstRelationship()
    {
        $images = Images::whereHas("tags", function ($q) {
            return $q->where("name", "Vulcan");
        })->whereHas("tags", function ($q) {
            return $q->where("name", "XH558");
        })
            ->with(["tags"]);
        dd($images->first()->toArray());
    }
}
