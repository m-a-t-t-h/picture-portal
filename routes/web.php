<?php

use App\Http\Api\FilterController;
use App\Http\Api\ImgSrvController;
use App\Http\Api\TreeController;
use App\Http\Api\AuthController;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

// ---- No auth middleware applied here as these routes are intended to be public facing
//
//      @todo Add rate limiting & blacklisting
//
Route::redirect("/", "/dw");
Route::get('/dw', [AppController::class, "get"]);
Route::get('/dw/{any?}', [AppController::class, "get"])->where('any', '.*');
Route::get("/tree", [TreeController::class, "get"]);
Route::get('/imgsrv/thumb/{hash}', [ImgSrvController::class, "getThumbnail"])->where('hash', '.*');
Route::get('/imgsrv/full/{hash}', [ImgSrvController::class, "getImage"])->where('hash', '.*');



// @todo Add CSRF protection here
Route::post("/results", [FilterController::class, "post"]);
Route::post("/api/image/{img_id}/info", [ImgSrvController::class, "info"]);

Route::group(["middleware" => "auth"], function () {
    Route::get('/auth/user', [AuthController::class, 'user']);

    Route::get("/api/logout", function () {
        Auth::logout();
    });
});
