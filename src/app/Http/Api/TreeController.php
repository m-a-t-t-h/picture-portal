<?php namespace App\Http\Api;

use App\Http\Controllers\Controller;
use App\Services\TreeServices;

class TreeController extends Controller
{
    public $useCache = FALSE;

    public function get()
    {
        $tree = TreeServices::getTree();
        return response($tree, 200)->header("Content-Type", "application/json");
    }
}
