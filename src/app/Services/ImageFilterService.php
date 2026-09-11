<?php namespace App\Services;

use App\Models\Images;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class ImageFilterService
{
    private Builder    $query;
    private Collection $results;
    private array      $tag_filters;
    private array      $camera_filter;
    private string     $order_by;
    private int        $page_size;
    private int        $page;
    private bool       $enforce_public_tag;
    private int        $collection_id;

    public function __construct()
    {
        $this->enforce_public_tag = config("dkw.REQUIRE_PUBLIC_TAG");
        $this->page_size          = config("dkw.PAGE_SIZE");
        $this->page               = 0;
        $this->order_by           = 7;
    }

    public function setEnforcePublicTag(bool $bool): self
    {
        $this->enforce_public_tag = $bool;

        return $this;
    }

    public function setTagFilter(array $tags): self
    {
        $this->tag_filters = $tags;

        return $this;
    }

    public function setOrderBy($value): self
    {
        $this->order_by = $value;

        return $this;
    }

    public function setPage(int $page_id): self
    {
        $this->page = $page_id;

        return $this;
    }

    public function setPageSize(int $page_size): self
    {
        $this->page_size = $page_size;

        return $this;
    }

    public function setCameraFilter(array $filter): self
    {
        $this->camera_filter = $filter;

        return $this;
    }

    public function setCollectionId(int $collection_id): self
    {
        $this->collection_id = $collection_id;

        return $this;
    }

    public function buildQuery(): self
    {
        $relations = [
            'imageAlbum.albumRoot',
            'imageInformation',
            'imagePosition',
            'imageMetadata',
            'imageTags',
            "tagChain",
        ];

        $query = $this->applyCollectionConstraint(Images::query());
        $query = $this->applyPublicTagConstraint($query);
        $query = $this->applySelectedTagsConstraint($query);
        $query = $this->applyCameraConstraint($query);
        $query = $this->applyImageFormatConstraint($query);
        $query = $this->applyOrdering($query);

        $this->query = $query->with($relations)
            ->addSelect(["img_hash" => Images::selectRaw("SHA2( CONCAT(Images.id, '/', Images.name) , 256) AS img_hash")->from("Images", "I2")->whereColumn("I2.id", "Images.id")])
            ->where("status", Images::STATUS_NORMAL)
            ->offset($this->page * $this->page_size)
            ->limit($this->page_size);

        return $this;
    }

    public function runQuery()
    {
        $query  = $this->query;
        $images = $query->get();

        $mapped = $images->map(function (Images $image) {

            // @todo Hardcoded excluded tags - https://github.com/m-a-t-t-h/picture-portal/issues/7
            $excluded_tags = ["1", "2829", "4"];
            $tag_ids       = NULL;
            $information   = $image->imageInformation;
            $metadata      = $image->imageMetadata;
            $filtered_tags = [];

            if (isset($information) && isset($information->format)) {
                if ($information->format === "MP3" || $information->format === "MP4") {
                    if (!AuthService::isMp3Mp4DirectAccessEnabled()) return NULL;
                }
            }

            $tags = $image->tagChain->toArray();
            if (count($tags)) {
                foreach ($tags as $tag) {
                    $tag_id   = $tag["tag_id"];
                    $tag_name = $tag["tag_name"];
                    $skip     = FALSE;
                    foreach ($excluded_tags as $excluded_tag) {
                        if (str_contains($tag["tag_chain"], "," . $excluded_tag . ",")) $skip = TRUE;
                    }
                    if (!$skip) {
                        $filtered_tags[] = [$tag_id, $tag_name];
                    }
                }
            }

            $response = [
                "tags"                  => $filtered_tags,
                "img_hash"              => $image->img_hash,
                "tag_ids"               => $tag_ids,
                'img_id'                => $image->id,
                'img_name'              => $image->name,
                'img_rating'            => $information?->rating,
                'img_creation_date'     => $information?->creationDate,
                'img_digitization_date' => $information?->digitizationDate,
                'img_width'             => $information?->width,
                'img_height'            => $information?->height,
                'img_format'            => $information?->format,
                'img_size'              => $image->filesize,
                'camera_make'           => $metadata?->make,
                'camera_model'          => $metadata?->model,
                'camera_lens'           => $metadata?->lens,
                'camera_aperture'       => $metadata?->aperture,
                'camera_focalLength'    => $metadata?->focalLength35,
                'camera_shutter'        => $metadata?->exposureTime,
                'camera_iso'            => $metadata?->sensitivity,
                "camera_white_balance"  => $metadata?->whiteBalance,
                "geo_lat"               => $image->imagePosition?->latitude,
                "geo_long"              => $image->imagePosition?->longitude,
            ];

            if (isset($information) && isset($information->format)) {
                if ($information->format === "MP3" || $information->format === "MP4") {
                    if (AuthService::isMp3Mp4DirectAccessEnabled()) {
                        $response["img_path"] =
                            config("dkw.IMAGE_URL_PREFIX") .
                            "svr" . config("dkw.ROOT_COLLECTION_ID") .
                            str_replace(config("dkw.IMAGE_URL_PREFIX_STRIP"), "", $image->path);
                    }
                }
            }

            return $response;
        });

        $this->results = $mapped;

        return $this;
    }

    public function getResults(): ?Collection
    {
        return $this->results ?? NULL;
    }

    public function toJson(): string
    {
        return isset($this->results) ? json_encode($this->results) : "";
    }

    protected function applyOrdering(Builder $query): Builder
    {
        if (!isset($this->order_by)) $this->order_by = 7;

        switch ($this->order_by) {
            case 1:
                $query->orderBy("Images.id");
                break;
            case 2:
                $query->orderByDesc("Images.id");
                break;
            case 3:
                $query->orderBy("Images.name");
                break;
            case 4:
                $query->orderByDesc("Images.name");
                break;
            case 5:
                $query->orderBy("imageInformation.digitizationDate");
                break;
            case 6:
                $query->orderByDesc("imageInformation.digitizationDate");
                break;
            case 7:
                $query->inRandomOrder();
                break;
            case 8:
                $query->leftJoin("ImageInformation", "id", "imageid");
                $query->orderBy("ImageInformation.rating");
                break;
            case 9:
                $query->leftJoin("ImageInformation", "id", "imageid");
                $query->orderByDesc("ImageInformation.rating");
                break;
        }

        return $query;
    }

    protected function applyCollectionConstraint(Builder $query): Builder
    {
        $query->whereHas('imageAlbum.albumRoot', function ($query) {
            $query->where('id', $this->collection_id ?? config("dkw.ROOT_COLLECTION_ID"));
        });

        return $query;
    }

    protected function applyCameraConstraint(Builder $query): Builder
    {
        if (!isset($this->camera_filter)) return $query;

        $query->whereHas("imageMetadata", function ($query) {
            $query->whereIn("make", $this->camera_filter);
        });

        return $query;
    }

    protected function applyImageFormatConstraint(Builder $query): Builder
    {
        $query->whereHas('imageInformation', function ($query) {
            $query->whereNotIn('format', ['RAW-NEF', 'RAW-DNG']);
        });

        return $query;
    }

    protected function applyPublicTagConstraint(Builder $query): Builder
    {
        if (!$this->enforce_public_tag) return $query;
        if (!AuthService::isPublicEnforced()) return $query;

        $query->whereHas("imageTags", function ($query) {
            $query->where("id", config("dkw.PUBLIC_TAG_ID"));
        });

        return $query;
    }

    protected function applySelectedTagsConstraint(Builder $query): Builder
    {
        if (!isset($this->tag_filters)) return $query;

        $query->whereHas('imageTags', function ($query) { $query->whereIn('id', $this->tag_filters); }, '=', count($this->tag_filters));

        return $query;
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     *
     * @deprecated
     */
    private function joinTagChain(Builder $query): Builder
    {
        $query
            ->leftJoin("ImageTags", "ImageTags.imageid", "=", "Images.id")
            ->leftJoin('tag_chain', "ImageTags.tagid", "=", "tag_id")
            ->select(DB::raw("*,GROUP_CONCAT(tag_chain.tag_path ORDER BY tag_id SEPARATOR '|') AS tag_path,
                                 GROUP_CONCAT(tag_chain.tag_chain ORDER BY tag_id SEPARATOR '|') AS tag_chain"))
            ->whereNotLike("tag_chain", ",1,%");

        return $query;
    }


}
