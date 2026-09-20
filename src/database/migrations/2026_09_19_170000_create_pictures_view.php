<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
CREATE VIEW Pictures AS (SELECT
    IM.id                                    AS img_id,
    IM.name                                  AS file_name,
    CONCAT(AL.relativePath, '/', IM.name)    AS file_path,
    UPPER(SUBSTRING_INDEX(IM.name, '.', -1)) AS file_ext,
    SHA2(CONCAT(AL.relativePath, '/', IM.name), 256) AS file_hash,
    II.width                                 AS img_width,
    II.height                                AS img_height,
    II.format                                AS img_format,
    IC.comment                               AS img_caption,
    II.rating                                AS img_rating,
    II.creationDate                          AS img_creationDate,
    II.colorDepth                            AS img_colorDepth,
    II.colorModel                            AS img_colorModel,
    MD.make                                  AS cam_make,
    MD.model                                 AS cam_model,
    MD.lens                                  AS cam_lens,
    MD.aperture                              AS cam_aperture,
    MD.focalLength                           AS cam_focalLength,
    concat('1/', 1/MD.exposureTime)          AS cam_exposure,
    MD.exposureTime                          AS cam_exposureDecimal,
    MD.exposureMode                          AS cam_exposureMode,
    MD.exposureProgram                       AS cam_exposureProgram,
    MD.whiteBalance                          AS cam_whiteBalance,
    MD.whiteBalanceColorTemperature          AS cam_colorTemperature,
    MD.meteringMode                          AS cam_meteringMode,
    IP.latitudeNumber                        AS geo_lat,
    IP.longitudeNumber                       AS geo_lon,
    IP.altitude                              AS geo_alt,
    IP.accuracy                              AS geo_accuracy,
    AR.id                                    AS file_albumId
FROM Images                IM
LEFT JOIN Albums           AL ON IM.album = AL.id
LEFT JOIN AlbumRoots       AR ON AL.albumRoot = AR.id
LEFT JOIN ImageComments    IC ON IC.imageid = IM.id
LEFT JOIN ImageInformation II ON II.imageid = IM.id
LEFT JOIN ImageMetadata    MD ON MD.imageid = IM.id
LEFT JOIN ImagePositions   IP ON IP.imageid = IM.id
WHERE (IC.type IS NULL OR IC.type = 3) AND IM.status = 1)
");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tag_excluded');
    }
};
