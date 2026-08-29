<?php

namespace App\Services;

use DB;
use Log;

trait ImageQueryMiddleware
{
    protected int $page;

    protected function newQuery(): self
    {
        $this->tag_filters       = [];
        $this->raw_query_results = [];

        return $this;
    }

    protected function prepareQuery(): self
    {
        Log::debug(__METHOD__);

        $page_size = config("dkw.PAGE_SIZE");
        $page      = $this->page * $page_size;

        $root_collection_id = config("dkw.ROOT_COLLECTION_ID");
        $tag_filters        = $this->tag_filters;
        $filter_definitions = "";
        $filter_references  = "(";

        $enforce_public = "";
        if (AuthService::isPublicEnforced()) {
            $public_tag_id  = config("dkw.PUBLIC_TAG_ID");
            $enforce_public = " AND tag_chain LIKE '%,$public_tag_id,%'";
        }

        $orderByStr = match ($this->order_by) {
            "1" => "img_id ASC",
            "2" => "img_id DESC",
            "3" => "img_name ASC",
            "4" => "img_name DESC",
            "5" => "img_digitization_date ASC",
            "6" => "img_digitization_date DESC",
        };

        switch ($this->order_by) {
            case 1: // id-asc
                $orderByStr = "img_id";
                break;

        }

        foreach ($tag_filters as $idx => $filter_tag_id) {
            $filter_definitions .= "\n    filter$filter_tag_id as (select IM.id from Images IM LEFT JOIN ImageTags IT on IM.id = IT.imageid where tagid = $filter_tag_id),";
            $filter_references  .= " IM.id in (SELECT id FROM filter$filter_tag_id) AND ";
        }
        $filter_references = substr($filter_references, 0, -5) . ")";

        $sql           = /**@lang MariaDB */
            <<<SQL

WITH $filter_definitions

     query1 AS (SELECT tc.tag_id,
                       tc.tag_name COLLATE utf8mb4_general_ci                         AS tag_name,
                       tc.tag_path COLLATE utf8mb4_general_ci                         AS tag_path,
                       tc.tag_chain COLLATE utf8mb4_general_ci                        AS tag_chain,
                       tc.tag_depth
                      -- (select count(*) from ImageTags IT where IT.tagid = tc.tag_id) as tag_img_count
                FROM tag_chain tc
                ORDER BY tag_chain desc),

     -- Exclude any tags listed in tag_excluded
     --
     query2 as (select * from query1 where not exists(select 1 from tag_excluded where find_in_set(tag_excluded.tag_id, tag_chain) > 0)),

     --  Exclude any DigiKam internal tags
     --
     query3 as (select * from query2 where tag_path not like '_Digikam_Internal_Tags_%'),

     --  The final query
     --
     query4 as (select  IM.id            AS  img_id,
                        IM.name          AS  img_name,
                        II.rating        AS  img_rating,
                        II.creationDate  AS  img_creation_date,
                        II.digitizationDate AS img_digitization_date,
                        tag_path         AS  img_tag_path,
                        II.width         AS  img_width,
                        II.height        AS  img_height,
                        II.format        AS  img_format,
                        IM.filesize      AS  img_size,
                        concat('/images', relativePath, '/', IM.name) AS img_path,
                        tag_chain

                from query3
                         LEFT JOIN ImageTags IT ON IT.tagid = tag_id
                         LEFT JOIN Images IM ON IM.id = IT.imageid
                         LEFT JOIN Albums AL ON AL.id = IM.album
                         LEFT JOIN AlbumRoots AR ON AR.id = AL.albumRoot
                         LEFT JOIN ImageInformation II ON II.imageid = IM.id
        WHERE   II.format<>'RAW-NEF'
                AND AR.id=$root_collection_id   
                AND $filter_references
                $enforce_public
    )

select * 
from query4
group by img_id
order by $orderByStr
LIMIT $page, $page_size
SQL;
        $this->raw_sql = $sql;
        Log::debug($sql);

        return $this;
    }

    protected function runQuery()
    {
        $this->raw_query_results = DB::select($this->raw_sql);
        Log::debug(count($this->raw_query_results) . " raw results");

        return $this;
    }

    protected function populateImageTags($do_it = TRUE): self
    {
        if (!$do_it) return $this;

        $sql = "SELECT TG.name, TG.id FROM Tags TG LEFT JOIN ImageTags IT ON IT.tagid=TG.id WHERE IT.imageid=? AND TG.pid NOT IN (1, 0, -1)";
        foreach ($this->raw_query_results as $image) {
            $tags        = DB::select($sql, [$image->img_id]);
            $image->tags = $tags;
        }

        return $this;
    }

    protected function setTagFilter(array $tags): self
    {
        Log::debug("--------------");
        Log::debug("");
        Log::debug("");
        Log::debug("Tag filters: [" . implode(",", $tags) . "]");

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

    protected function debugLogQuery($do_it = TRUE): self
    {
        if ($do_it) Log::debug($this->raw_sql);

        return $this;
    }

    protected function debugLogResults($do_it = TRUE): self
    {
        if ($do_it) Log::debug(count($this->raw_query_results) . " results");

        return $this;
    }

    protected function getResults(): array
    {
        return $this->raw_query_results;
    }

    protected function getJsonResults(): string
    {
        return json_encode($this->getResults());
    }
}
