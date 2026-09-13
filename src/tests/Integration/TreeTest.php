<?php namespace Integration;

use App\Services\ImgSrv;
use App\Services\TreeServices;
use Tests\TestCase;

/**
 * @bug  These tests are brittle and rely on a specific set of tagged images in my reference database. They won't work for you.
 * @todo Decouple the reference database from the unit tests.
 *
 * If tests randomly fail, trace back the tag chain to ensure I've not updated anything in DigiKam that breaks the tags.
 */
class TreeTest extends TestCase
{
   public function testTree() {

       $tree = TreeServices::getTree();

       dd($tree);
   }
}
