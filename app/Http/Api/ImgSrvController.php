<?php namespace App\Http\Api;

use App\Http\Controllers\Controller;
use App\Services\ImgSrv;

class ImgSrvController extends Controller
{
    public function getThumbnail($hash)
    {
        try {
            $path   = ImgSrv::hashToPath($hash);
            $server = app('glide.server');

            return $server->getImageResponse($path, ["h" => 400]);
        }
        catch (\Exception $e) {
            \Log::error($e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
            // @todo Return a placeholder image
        }
    }

    /**
     * @param $hash string
     *
     * @return string
     *
     * @todo Return placeholder image when not found
     */
    public function getImage(string $hash): string
    {
        try {
            $response = app('glide.server')->getImageResponse(ImgSrv::hashToPath($hash), ["w" => 280]);

            return $response;
        }
        catch (\Exception $e) {
            \Log::error($e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
        }
    }

    /**
     * @param $img_id
     *
     * @return \Illuminate\Http\JsonResponse
     *
     * @todo Eloquentify this query, in a service method
     */
    public function info($img_id)
    {
        $sql = <<<SQL

SELECT * FROM Images
LEFT JOIN ImageInformation II ON II.imageid=Images.id
LEFT JOIN ImageMetadata    IM ON IM.imageid=Images.id
LEFT JOIN ImageProperties  IP ON IP.imageid=Images.id
LEFT JOIN ImagePositions   LL ON LL.imageid=Images.id
WHERE Images.id=?
SQL;

        $rst = \DB::select($sql, [$img_id]);

        return response()->json($rst);
    }
}
