<?php namespace App\Http\Api;

use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use App\Services\TreeServices;

class TreeController extends Controller
{
    public function get():Response
    {
        $tree = TreeServices::getTree();
        return response($tree, 200)->header("Content-Type", "application/json");
    }
}
