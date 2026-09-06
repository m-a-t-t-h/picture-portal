<?php namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Images extends Model
{
    public $table = "Images";

    public function imageTags(): HasManyThrough
    {
        return $this->hasManyThrough(
            Tags::class,
            ImageTags::class,
            "imageid",
            "id",
            "id",
            "tagid",
        );
    }

    public function imageAlbum(): HasOne
    {
        return $this->hasOne(Albums::class, "id", "album");
    }

    public function imageInformation(): HasOne
    {
        return $this->hasOne(ImageInformation::class, "imageid", "id");
    }

    public function imagePosition(): HasOne
    {
        return $this->hasOne(ImagePositions::class, "imageid", "id");
    }

    public function imageMetadata(): HasOne
    {
        return $this->hasOne(ImageMetadata::class, "imageid", "id");
    }

    public function path(): Attribute
    {
        return Attribute::make(
            get: function () {
                $path = $this->album()->first()->relativePath;
                if (!str_starts_with($path, "/")) $path = "/" . $path;

                return "/images" . $path;
            }
        );
    }
}
