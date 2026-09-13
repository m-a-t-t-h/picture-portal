<?php namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Glide\Responses\SymfonyResponseFactory;
use League\Glide\ServerFactory;
use Illuminate\Support\Facades\Log;

class PicturePortalServiceProvider extends ServiceProvider
{
    public function boot(): void
    {

        RateLimiter::for('images', function (Request $request) {
            return Limit::perSecond(60)->by($request->ip());
        });

    }

    public function register(): void
    {
        $this->app->singleton("glide.server", function () {
            $cache  = app()->isProduction() ? "glide_s3_cache" : "glide_local_cache";
            Log::debug("Cache destination: $cache");
            $server = ServerFactory::create([
                'source'         => Storage::disk("pictureportal_source")->getDriver(),
                'cache'          => Storage::disk($cache)->getDriver(),
                'max_image_size' => 2000 * 2000,
                'response'       => new SymfonyResponseFactory(app('request')),
            ]);

            $server->setBaseUrl("/");

            return $server;
        });
    }
}
