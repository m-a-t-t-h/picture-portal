<?php

namespace App\Services;

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

        $sql = <<<SQL

  WITH
      q1 AS (SELECT CONCAT(relativePath, '/', IM.name) AS img_path, IM.id AS img_id
                FROM Images IM LEFT JOIN Albums ON Albums.id = IM.album
               WHERE status = 1 AND Albums.albumRoot = $root_collection_id),
      q2 AS (SELECT  img_id, SHA2(img_path, 256) AS img_hash FROM q1),
      q3 AS (SELECT  img_hash, regexp_replace(concat('$image_url_prefix', relativePath, '/', IM.name), '$image_url_prefix_strip', '') AS img_path
                FROM q2 LEFT JOIN Images IM ON IM.id = img_id LEFT JOIN Albums AL ON AL.id = IM.album)
  SELECT img_path FROM q3 WHERE img_hash = ?
SQL;

        $rst = \DB::select($sql, [$hash]);
        if ($rst) $path = $rst[0]->img_path;

        return $path;
    }
}
