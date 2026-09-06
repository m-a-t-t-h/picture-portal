<?php namespace App\Services;

use App\Models\Images;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

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
        ];

        $query = Images::query();
        $query = $this->applyCollectionConstraint($query);
        $query = $this->applyPublicTagConstraint($query);
        $query = $this->applySelectedTagsConstraint($query);
        $query = $this->applyCameraConstraint($query);
        $query = $this->applyImageFormatConstraint($query);
        $query = $this->applyOrdering($query);
        //$query = $this->joinTagChain($query);

        $query->with($relations)
            ->addSelect(["img_hash" => Images::selectRaw("SHA2( CONCAT(Images.id, '/', Images.name) , 256) AS img_hash")->from("Images", "I2")->whereColumn("I2.id", "Images.id")])
            ->where("status", Images::STATUS_NORMAL)
            ->offset($this->page * $this->page_size)
            ->limit($this->page_size);

        $this->query = $query;

        return $this;
    }

    public function runQuery()
    {
        $query  = $this->query;
        $images = $query->get();

        $mapped = $images->map(function (Images $image) {
            $tag_parts = NULL;
            $tag_ids   = NULL;

            $information = $image->imageInformation;
            $metadata    = $image->imageMetadata;

            if (isset($image["tag_path"])) {
                $excluded_tags = ["1", "2829"];

                $tag_path  = explode("|", $image["tag_path"]);
                $tag_chain = explode("|", $image["tag_chain"]);

                $filtered_path  = [];
                $filtered_chain = [];
                for ($i = 0; $i < count($tag_chain); $i++) {

                    // ---- Split the tag_chain by comma, iterate it and reject any tag IDs in the $excluded_tags array
                    //
                    $chain = explode(",", $tag_chain[$i]);
                    $skip  = FALSE;
                    foreach ($chain as $c) foreach ($excluded_tags as $e) $skip = $skip || $e === $c;
                    if (!$skip) {
                        $filtered_chain[] = $tag_chain[$i];
                        $filtered_path[]  = $tag_path[$i];
                    }
                }

                $tag_parts = implode(",", array_map(fn($x) => basename($x), $filtered_path));
                $tag_ids   = implode(",", array_map(function ($x) {
                    $parts = explode(',', $x);

                    return $parts[count($parts) - 2];
                }, $filtered_chain));
            }

            return [
                "img_hash"              => $image->img_hash,
                "tags"                  => $tag_parts,
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
                'camera_focalLength'    => $metadata?->focalLength,
                'camera_iso'            => $metadata?->sensitivity,
            ];
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

        $query->whereHas("imageMetadata", function ($query) { $query->whereIn("make", $this->camera_filter); });

        return $query;
    }

    protected function applyImageFormatConstraint(Builder $query): Builder
    {
        $query->whereHas('imageInformation', function ($query) { $query->where('format', '<>', 'RAW-NEF'); });

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

    protected function applyOrdering(Builder $query): Builder
    {
        if ($this->order_by === "7") {
            $query->inRandomOrder();
        } else {
            $order = match ($this->order_by) {
                "1" => "Images.id ASC",
                "2" => "Images.id DESC",
                "3" => "Images.name ASC",
                "4" => "Images.name DESC",
                "5" => "",
                "6" => "",
            };
            $query->orderBy($order);
        }

        return $query;
    }

    private function joinTagChain(Builder $query): Builder
    {
        $query
            ->leftJoin("ImageTags", "ImageTags.imageid", "=", "Images.id")
            ->leftJoin('tag_chain', "ImageTags.tagid", "=", "tag_id")
            ->select(\DB::raw("*,GROUP_CONCAT(tag_chain.tag_path ORDER BY tag_id SEPARATOR '|') AS tag_path,
                                 GROUP_CONCAT(tag_chain.tag_chain ORDER BY tag_id SEPARATOR '|') AS tag_chain"))
            ->whereNotLike("tag_chain", ",1,%");

        return $query;
    }


}
