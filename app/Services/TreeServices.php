<?php

namespace App\Services;

use App\Models\Tags;
use DB;

class TreeServices
{
    public static function getTree()
    {
        $root_ids = json_decode(config("dkw.ROOT_TAG_ARRAY"), TRUE);
        $tree     = [];

        foreach ($root_ids as $root_tag_id => $tag_label) {
            $children = Tags::createTreeDownwards($root_tag_id);
            $tree[]   = Tags::createTreeNode($root_tag_id, $tag_label, $children);
        }

        $tree = AuthService::isPublicEnforced() ? self::pruneTree($tree) : $tree;

        return $tree;
    }

    protected static function pruneTree(array $tree)
    {
        $public_tag_id = config("dkw.PUBLIC_TAG_ID");

        // ---- Filter the tag tree to remove any tags not referenced by "Public" images
        //
        $sql          = <<<SQL
            WITH images_with_public_tag as (
                SELECT DISTINCT(imageid)
                    FROM ImageTags
                    LEFT JOIN tag_chain ON tag_chain.tag_id = tagid
                    WHERE tagid = $public_tag_id)
            
            SELECT distinct(tag_path), tagid
            FROM images_with_public_tag IPT
                LEFT JOIN ImageTags IT  ON IT.imageid = IPT.imageid
                LEFT JOIN Tags      TG  ON TG.id = IT.tagid and TG.pid not in (1, 0, -1)
                LEFT JOIN tag_chain TC  ON TC.tag_id = TG.id
            WHERE tag_path <> ''
            ORDER BY tag_path
SQL;
        $allowed_tags = DB::select($sql);
        $tree         = [];

        return Tags::buildHierarchy($allowed_tags);
    }
}
