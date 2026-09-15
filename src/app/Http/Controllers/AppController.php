<?php namespace App\Http\Controllers;

use Illuminate\View\View;

class AppController extends Controller
{
    public function get():View
    {
      return view("main");
    }
}
