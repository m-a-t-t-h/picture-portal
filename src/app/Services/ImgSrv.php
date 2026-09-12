<?php namespace App\Services;

use Illuminate\Support\Facades\DB;

class ImgSrv
{
    public static function getImageByHash(string $hash)
    {
        /** @var \League\Glide\Server $server */
        $server = app("glide.server");
        $path   = ImgSrv::hashToPath($hash);
        $server->outputImage($path, ["w" => 2600]);
    }

    public static function getThumbnailByHash(string $hash)
    {
        /** @var \League\Glide\Server $server */
        $server = app("glide.server");
        $path   = ImgSrv::hashToPath($hash);
        $server->outputImage($path, ["h" => 400]);
    }

    /**
     * Resolve a SHA256 hash of the image path back to the image path
     *
     * @param $hash
     *
     * @return string
     */
    public static function hashToPath($hash, $image_url_prefix = "", $image_url_prefix_strip = ""): string
    {
        $root_collection_id = config("dkw.ROOT_COLLECTION_ID");

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

        $rst  = DB::select($sql, [$hash]);
        $path = $rst ? "col" . $root_collection_id . $rst[0]->img_path : "";

        return $path;
    }
}
