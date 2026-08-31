<?php

use App\Http\Api\ApiFilterController;
use App\Http\Api\ApiImageController;
use App\Http\Api\ApiTreeController;
use App\Http\Api\AuthController;
use App\Http\Controllers\MainController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Auth::routes();

// ---- No auth middleware applied here as these routes are intended to be public facing
//
//      @todo Add rate limiting & blacklisting
//
Route::redirect("/", "/dw");
Route::get('/dw', [MainController::class, "get"]);
Route::get('/dw/{any?}', [MainController::class, "get"])->where('any', '.*');
Route::get('/auth/user', [AuthController::class, 'user']);
Route::get("/tree", [ApiTreeController::class, "get"]);

// @todo Add CSRF protection here
Route::post("/results", [ApiFilterController::class, "post"]);
Route::post("/api/image/{img_id}/info", [ApiImageController::class, "info"]);

Route::group(["middleware" => "auth"], function () {
    Route::post("/api/logout", function () {
        Auth::logout();
    });
});
