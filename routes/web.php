<?php

use App\Http\Api\AuthController;
use App\Http\Api\FilterController;
use App\Http\Api\ImgSrvController;
use App\Http\Api\TreeController;
use App\Http\Controllers\AppController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

// ---- Public facing routes, no auth middleware
//
//      @todo Add rate limiting & blacklisting
//
Route::redirect("/", "/dw");
Route::get('/dw', [AppController::class, "get"]);
Route::get("/dw/tree", [TreeController::class, "get"]);
Route::get('/dw/imgsrv/thumb/{hash}', [ImgSrvController::class, "getThumbnail"])->where('hash', '.*');
Route::get('/dw/imgsrv/full/{hash}',  [ImgSrvController::class, "getImage"])    ->where('hash', '.*');
Route::get('/dw/auth/authed', [AuthController::class, 'isAuthed']);
Route::get('/dw/{any?}', [AppController::class, "get"])->where('any', '.*');

// @todo Add CSRF protection here
Route::post("/dw/results", [FilterController::class, "post"]);
Route::post("/dw/image/{img_id}/info", [ImgSrvController::class, "info"]);

Route::group(["middleware" => "auth"], function () {
    Route::get('/auth/user', [AuthController::class, 'user']);

    Route::get("/api/logout", function () { Auth::logout(); });
});
