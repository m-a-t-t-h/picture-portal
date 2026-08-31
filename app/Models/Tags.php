<?php namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class Tags extends Model
{
    public $table = "Tags";

    public function scopeToplevel($q)
    {
        return $q->where("pid", 0)->where("name", "!=", "_Digikam_Internal_Tags_");
//        return $q->where
    }

    public static function match($label)
    {
        $ret = [];

        $matches = Tags::where("name", $label)->get();
        foreach ($matches as $match) {
            $parent     = $match->pid;
            $path_parts = [$label];
            do {
                $node         = Tags::where("id", $parent)->first();
                $path_parts[] = $node->name;
                $parent       = $node->pid;
            } while ($parent > 0);

            $path_parts = join(":", array_reverse($path_parts));
            $ret[]      = $path_parts;
        }

        return $matches;
    }

    public function path()
    {
        $path   = "," . $this->name;
        $tag_id = $this->pid;
        do {
            $next   = Tags::where("id", $tag_id)->first();
            $path   = "," . $next->name . $path;
            $tag_id = $next->pid;
        } while ($tag_id);

        return $path;
    }

    public static function createTreeDownwards($parent_id = 0, $parent_path = ""): array
    {
        $tree  = [];
        $nodes = Tags::where("pid", $parent_id)->orderBy("name")->get();

        foreach ($nodes as $node) {
            $node_label = $node->name;
            $children   = self::createTreeDownwards($node->id);
            $tree[]     = self::createTreeNode($node->id, $node_label, $children);
        }

        return $tree;
    }

    public static function createTreeNode($id, $label, $children): array
    {
        return [
            "id"       => $id,
            "title"    => $label,
            "children" => $children,
        ];
    }

    public static function buildHierarchy(array $items): array
    {
        $tree = [];
        foreach ($items as $item) {
            $tag_path = $item->tag_path;
            $tag_id   = $item->tagid;
            $parts    = explode('/', $tag_path);
            $current  =& $tree;

            foreach ($parts as $index => $label) {
                $key = array_search($label, array_column($current, 'title'), TRUE);
                if ($key === FALSE) {
                    $current[] = [
                        'id'       => $index === count($parts) - 1 ? $tag_id : NULL,
                        'title'    => $label,
                        'children' => [],
                    ];
                    $key       = array_key_last($current);
                } elseif ($index === count($parts) - 1) {
                    $current[$key]['id'] = $tag_id;
                }
                $current =& $current[$key]['children'];
            }
            unset($current);
        }

        return $tree;
    }

    public static function createTreeUpwards($node_id, array $tree = [])
    {
        $debug = count($tree);

        do {
            $rst = DB::select("SELECT * FROM Tags WHERE id=$node_id")[0];
            if (array_key_exists($rst->name, $tree)) {
                dd("?");
            }
            $tree = [
                "id"       => $node_id,
                "name"     => $rst->name,
                "children" => $tree,
            ];

        } while ($node_id = $rst->pid);

        return $tree;
    }

    /**
     * Update the OnThisDay tag for the specified, or current, date
     *
     * @return void
     *
     * @bug I'm not 100% convinced DigiKam's digitizationDate is 100% accurate
     */
    public static function updateOnThisDayTags($month = NULL, $day = NULL)
    {
        $tag_id   = config("dkw.ON_THIS_DAY_TAG_ID");
        $album_id = config("dkw.ROOT_COLLECTION_ID");

        $month = $month ?: date("N");
        $day   = $day ?: date("d");

        DB::statement("DELETE FROM ImageTags WHERE tagid=?", [$tag_id]);

        $sql = <<<SQL
            INSERT INTO ImageTags (tagid, imageid)
            SELECT ?, IM.id
            FROM Images IM
                LEFT JOIN ImageInformation  II ON II.imageid=IM.id
                LEFT JOIN Albums            AL ON AL.id=IM.album
                LEFT JOIN AlbumRoots        AR ON AR.id=AL.albumRoot
            
            WHERE
                AR.id=? AND
                month(digitizationDate) = ? AND
                dayofmonth(digitizationDate) = ?
            
            GROUP BY IM.id;
SQL;
        \Log::debug($sql);

        DB::query($sql, [$tag_id, $album_id, $month, $day]);

    }
}
