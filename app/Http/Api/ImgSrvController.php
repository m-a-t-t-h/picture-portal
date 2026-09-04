<?php namespace App\Http\Api;

use App\Http\Controllers\Controller;
use App\Services\ImgSrv;

class ImgSrvController extends Controller
{
    public function getThumbnail($hash)
    {
        try {
            $server = app('glide.server');
            $path   = ImgSrv::hashToPath($hash);

            return $server->getImageResponse($path, ["h" => 400]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            // @todo Return a placeholder image
        }
    }

    public function getImage($hash)
    {
        try {
            $server = app('glide.server');
            $path   = ImgSrv::hashToPath($hash);

            return $server->getImageResponse($path, ["w" => 1280]);
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            // @todo Return a placeholder image
        }
    }

    public function info($img_id)
    {
        \Log::debug("Getting image info for $img_id");

        $sql = <<<SQL

SELECT * FROM Images
LEFT JOIN ImageInformation II ON II.imageid=Images.id
LEFT JOIN ImageMetadata    IM ON IM.imageid=Images.id
LEFT JOIN ImageProperties  IP ON IP.imageid=Images.id
LEFT JOIN ImagePositions   LL ON LL.imageid=Images.id
WHERE Images.id=?
SQL;

        $rst = \DB::select($sql, [$img_id]);
        \Log::debug($rst);

        return response()->json($rst);
    }
}
