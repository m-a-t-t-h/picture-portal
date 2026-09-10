<?php namespace App\Http\Api;

use App\Http\Controllers\Controller;
use App\Services\ImgSrv;

class ImgSrvController extends Controller
{
    /**
     * Get thumbnail of image by its hash
     *
     * @param $hash string
     *
     * @return string
     *
     * @todo Return placeholder image when not found
     */

    public function getThumbnail($hash)
    {
        try {
            $response = app('glide.server')->getImageResponse(ImgSrv::hashToPath($hash), ["h" => 400]);

            return $response;
        }
        catch (\Exception $e) {
            \Log::error($e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
        }
    }

    /**
     * Get full sized image by hash
     *
     * @param $hash string
     *
     * @return string
     *
     * @todo Return placeholder image when not found
     */
    public function getImage(string $hash)
    {
        try {
            $response = app('glide.server')->getImageResponse(ImgSrv::hashToPath($hash), ["w" => 1280]);

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
