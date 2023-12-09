<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\GalleryController;
/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::prefix('gallery')->group(function () {
    Route::get('/', [GalleryController::class, 'index']);
    Route::get('/image/{image_name}', [GalleryController::class, 'getImage']);
    Route::post('/', [GalleryController::class, 'store']);
});
