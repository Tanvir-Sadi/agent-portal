<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ApplicationController;


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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::resource('application', ApplicationController::class);
    Route::post('application/{id}/upload', [ApplicationController::class, 'upload']);
});

Route::prefix('admin')->group(function () {
    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::get('/agent-request', [AuthController::class, 'agent']);
        Route::post('/verify-agent/{id}', [AuthController::class, 'verify']);
    });
    
    Route::middleware(['auth:sanctum'])->group(function () {
        Route::resource('university', UniversityController::class);
        Route::resource('course', CourseController::class);
    });
});


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/status', function () {
    return 'ok';
});
