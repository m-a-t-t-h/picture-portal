<?php namespace App\Http\Api;

use App\Http\Controllers\Controller;
use App\Services\AuthService;
use App\Services\ImageQueryMiddleware;
use Log;

class ApiFilterController extends Controller
{
    use ImageQueryMiddleware;

    protected array  $raw_query_results;
    protected array  $tag_filters;
    protected string $raw_sql;

    public function post(array $filter_tags = [], $page = 0)
    {
        Log::debug(__METHOD__);

        $do_logging = FALSE;
        $results    = [];

        $body = request()->all();

        if (!count($filter_tags)) {
            $filter_tags = $body["filter"];
            $filter_tags = json_decode($filter_tags, TRUE);

            if (!count($filter_tags)) {
                // ---- If no filter is specified, check if we're restricted to public only
                //      and return the first page.
                if (AuthService::isPublicEnforced()) {
                    $filter_tags = [config("dkw.PUBLIC_TAG_ID")];
                }
            }
        }

        if (!$page) {
            $page = $body["page"];
        }

        if (count($filter_tags)) {

            $results = $this->newQuery()
                ->setTagFilter($filter_tags)
                ->setPage($page)
                ->prepareQuery()
                ->debugLogQuery($do_logging)
                ->runQuery()
                ->populateImageTags()
                ->debugLogResults($do_logging)
                ->getJsonResults();
        }

        return response($results, 200)->header("Content-Type", "application/json");
    }
}
