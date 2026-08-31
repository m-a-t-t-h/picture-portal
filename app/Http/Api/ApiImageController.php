<?php namespace App\Http\Api;

use App\Http\Controllers\Controller;

class ApiImageController extends Controller
{
    public $useCache = FALSE;

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

        return response()->json($rst);
    }
}
