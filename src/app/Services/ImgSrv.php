<?php namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImgSrv
{
    /**
     * Resolve a SHA256 hash of the image path back to the image path
     *
     * @param $hash
     *
     * @return string
     */
    public static function hashToPath($hash): string
    {
        $image_url_prefix       = "";//config("dkw.IMAGE_URL_PREFIX");
        $image_url_prefix_strip = config("dkw.IMAGE_URL_PREFIX_STRIP");
        $root_collection_id     = config("dkw.ROOT_COLLECTION_ID");
        $path                   = "";

        // @todo Optimise this query. q1 is a full table scan.
        $sql = <<<SQL
  WITH
      q1 AS (   SELECT CONCAT(relativePath, '/', IM.name) AS img_path, 
                    IM.name as img_name,
                    IM.id AS img_id
                FROM Images IM 
                LEFT JOIN Albums ON Albums.id = IM.album
                WHERE status = 1 AND Albums.albumRoot = $root_collection_id
                ),
      
      q2 AS (   SELECT  SHA2(CONCAT(img_id, '/', img_name), 256) AS img_hash, img_path FROM q1 )
  
SELECT img_path FROM q2 WHERE img_hash=?

SQL;

        $rst = DB::select($sql, [$hash]);
        $ret = $rst ? "/svr" . $root_collection_id . $rst[0]->img_path : "";

        Log::debug("Resolved hash to $ret");
        return $ret;

    }
}
