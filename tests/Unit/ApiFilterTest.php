<?php

namespace Tests\Unit;

use App\Http\Api\ApiFilterController;
use App\Models\Albums;
use App\Models\Images;
use App\Models\Tags;
use Tests\TestCase;

/**
 * @todo
 */
class ApiFilterTest extends TestCase
{
    public function test1(): void
    {
       $controller = new ApiFilterController();
       $results = $controller->runQuery([350, 539, 254]);
       dd($results);
    }
    public function test2(): void
    {
        $controller = new ApiFilterController();
        $results = $controller->runQuery([350]);
        dd($results);
    }

    public function test3() {
        $controller = new ApiFilterController();
        $results = $controller->post([2829]);
        dd($results);
    }

}
