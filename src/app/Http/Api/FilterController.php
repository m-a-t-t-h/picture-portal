<?php namespace App\Http\Api;

use Illuminate\Http\Response;
use App\Services\AuthService;
use App\Http\Controllers\Controller;
use App\Services\ImageFilterService;
use App\Services\ImageQueryMiddleware;

class FilterController extends Controller
{
    use ImageQueryMiddleware;

    protected array  $raw_query_results;
    protected array  $tag_filters;
    protected string $raw_sql;

    public function post(array $tag_filter = [], $page = 0, $orderBy = 0): Response
    {
        $results = NULL;
        $body    = request()->all();
        if (!$page) $page = $body["page"];
        if (!$orderBy) $orderBy = $body["orderBy"];

        if (!count($tag_filter)) {
            $tag_filter = json_decode($body["filter"], TRUE);

            if (!count($tag_filter)) {
                // ---- If no filter is specified, check if we're restricted to public only and return the first page.
                if (AuthService::isPublicEnforced()) {
                    $tag_filter = [config("dkw.PUBLIC_TAG_ID")];
                }
            }
        }

        if (count($tag_filter)) {
            $results = new ImageFilterService()
                ->setPageSize(config("dkw.PAGE_SIZE"))
                ->setPage($page)
                ->setTagFilter($tag_filter)
                ->setOrderBy($orderBy)
                ->buildQuery()->runQuery()->toJson();
        }

        return response($results, 200)->header("Content-Type", "application/json");
    }
}
