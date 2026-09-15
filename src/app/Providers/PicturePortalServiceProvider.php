<?php namespace App\Providers;

use Illuminate\Http\Request;
use League\Glide\ServerFactory;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Storage;
use App\Services\LaravelResponseFactory;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;

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
            $source = config("dkw.IMAGE_SOURCE_DISK", "pictureportal_source");
            $cache  = config("dkw.GLIDE_CACHE_DISK");

            $server = ServerFactory::create([
                'source'         => Storage::disk($source)->getDriver(),
                'cache'          => Storage::disk($cache)->getDriver(),
                'max_image_size' => 2000 * 2000,
                'response'       => new LaravelResponseFactory(app('request')),
            ]);

            $server->setBaseUrl("/");

            return $server;
        });
    }
}
