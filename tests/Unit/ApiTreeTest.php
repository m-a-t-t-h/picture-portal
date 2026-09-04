<?php

namespace Tests\Unit;

use App\Http\Api\FilterController;
use App\Http\Api\TreeController;
use App\Models\Albums;
use App\Models\Images;
use App\Models\Tags;
use Tests\TestCase;

/**
 * @todo
 */
class ApiTreeTest extends TestCase
{
    public function test1(): void
    {
        $results = Tags::createTreeUpwards(1574);
        $results = Tags::createTreeUpwards(540, $results);
        dd($results);
    }

    public function testRootLevelTagFiltering()
    {
        $results = Tags::createTreeDownwards(1090);
        dd($results);
    }

    public function testPublicFiltering()
    {
        $tree = \App\Services\TreeServices::getTree();
    }
}
