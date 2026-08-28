<?php namespace App\Http\Api;

use App\Http\Controllers\Controller;
use App\Services\TreeServices;

class ApiTreeController extends Controller
{
    public $useCache = FALSE;

    public function get()
    {
        $tree_cache = storage_path("app/private") . "/tree.json";
        if ($this->useCache && file_exists($tree_cache)) {
            $tree = file_get_contents($tree_cache);

            return response($tree, 200)->header("Content-Type", "application/json");
        }

        $tree = TreeServices::getTree();

        if ($this->useCache) file_put_contents($tree_cache, json_encode($tree));

        return response($tree, 200)->header("Content-Type", "application/json");
    }
}
