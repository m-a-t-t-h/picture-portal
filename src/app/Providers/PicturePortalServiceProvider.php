<?php namespace App\Providers;

use Aws\S3\S3Client;
use Illuminate\Filesystem\AwsS3V3Adapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\ServiceProvider;
use League\Glide\Responses\SymfonyResponseFactory;
use League\Glide\ServerFactory;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;

class PicturePortalServiceProvider extends ServiceProvider
{
    public function boot():void {

        RateLimiter::for('images', function (Request $request) {
            return Limit::perSecond(60)->by($request->ip());
        });

    }
    public function register(): void
    {
        $this->app->singleton('glide.server', function () {
            return ServerFactory::create([
                'source'         => Storage::disk('images_source')->getDriver(),
                'cache'          => Storage::disk('glide_cache')->getDriver(),
                'max_image_size' => 2000 * 2000,
                'response'       => new SymfonyResponseFactory(app('request')),
            ]);
        });
    }
}
