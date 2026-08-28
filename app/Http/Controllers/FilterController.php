<?php namespace App\Http\Controllers;

use App\Models\Tags;

class FilterController extends Controller
{
    public function get()
    {
        $tree = Tags::createTreeDownwards();

        return view("filters")->with(["tree" => $tree]);
    }
}
