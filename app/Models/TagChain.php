<?php namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class TagChain extends Model
{
    public $table = "tag_chain";

    /**
     * Rebuild the tag chain structure
     *
     * @return void
     */
    public static function rebuild()
    {
        DB::statement("TRUNCATE TABLE tag_chain");
        $tags_list = Tags::where("id", ">", 0)->get();

        foreach ($tags_list as $tag) {

            $pid       = $tag->pid;
            $tag_path  = $tag->name;
            $tag_chain = $tag->id;
            $depth     = 1;

            while ($pid) {
                $parent    = Tags::where("id", $pid)->first();
                $tag_path  = $parent->name . "/" . $tag_path;
                $tag_chain = $parent->id . "," . $tag_chain;
                $pid       = $parent->pid;
                $depth++;
            }
            $tag_chain = ",$tag_chain,";

            DB::insert("INSERT INTO tag_chain VALUES (?, ?, ?, ?, ?)", [$tag->id, $tag->name, $tag_path, $tag_chain, $depth]);
        }
    }
}
