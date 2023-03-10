<?php

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\FileController;
use App\Http\Controllers\SpaceController;
use App\Http\Controllers\PublicDownloadController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('auth')->group(static function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('login', [AuthController::class, 'login']);
    Route::middleware('jwt.auth')->post('logout', [AuthController::class, 'logout']);
});

Route::middleware('jwt.auth')->group(static function () {
    Route::prefix('user')->group(static function () {
        Route::get('/', [UserController::class, 'show']);
        Route::patch('/', [UserController::class, 'update']);
    });

    Route::prefix('files')->group(static function () {
        Route::get('/', [FileController::class, 'index']);
        Route::get('/{folder}', [FileController::class, 'index']);
        Route::post('/upload', [FileController::class, 'upload']);
        Route::get('/download/{file}', [FileController::class, 'download']);
        Route::patch('/{file}', [FileController::class, 'update']);
        Route::delete('/{file}', [FileController::class, 'delete']);
    });

    Route::prefix('space')->group(static function() {
        Route::get('/', [SpaceController::class, 'total']);
        Route::get('/{folder}', [SpaceController::class, 'folder']);
    });
});

Route::get('public', PublicDownloadController::class)->name('public.images');

Route::fallback(static function() {
    return new JsonResponse(['message' => 'Неизвестный метод'], 404);
});
