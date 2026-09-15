<?php namespace App\Services;

use Exception;
use League\Glide\Server;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ImgSrv
{
    public static function getImageByHash(string $hash)
    {
        /** @var Server $server */
        try {
            $server = app("glide.server");
            $path   = ImgSrv::hashToPath($hash);

            return $server->getImageResponse($path, ["w" => 1280]);
        }
        catch (Exception $e) {
            Log::error($e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());

            return null;
        }
    }

    public static function getThumbnailByHash(string $hash)
    {
        /** @var Server $server */
        try {
            $server = app("glide.server");
            $path   = ImgSrv::hashToPath($hash);

            return $server->getImageResponse($path, ["h" => 400]);

        }
        catch (Exception $e) {
            Log::error($e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());

            return null;
        }
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

        // @todo Optimise this query. q1 is a full table scan.
        $sql = <<<SQL
  WITH
      q1 AS (   SELECT CONCAT(relativePath, '/', IM.name) AS img_path, IM.name as img_name,IM.id AS img_id
                FROM Images IM LEFT JOIN Albums ON Albums.id = IM.album
                WHERE status = 1 AND Albums.albumRoot = ?),
      q2 AS (   SELECT  SHA2(CONCAT(img_id, '/', img_name), 256) AS img_hash, img_path FROM q1 )
SELECT img_path FROM q2 WHERE img_hash=?
SQL;

        $root_collection_id = config("dkw.ROOT_COLLECTION_ID");

        $rst   = DB::select($sql, [$root_collection_id, $hash]);
        $path  = $rst ? "col" . $root_collection_id . $rst[0]->img_path : "";
        $strip = config("dkw.IMAGE_URL_PREFIX_STRIP");
        $path  = $strip ? str_replace($strip, "", $path) : $path;

        return $path;
    }
}
