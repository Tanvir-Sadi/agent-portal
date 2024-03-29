<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\UniversityController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ApplicationController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\IntakeController;
use App\Http\Controllers\LevelController;
use App\Http\Controllers\DocumentController;


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
Route::get('/logout', [AuthController::class,'logout']);
Route::get('application/{application}/downloadall', [ApplicationController::class, 'downloadAll']);

Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('notification', [AuthController::class, 'notification']);
    Route::post('uploadasagent', [AuthController::class, 'uploadMediaAsAgent']);
    Route::get('getasagent', [AuthController::class, 'getMediaAsAgent']);
    Route::post('application/{id}/upload', [ApplicationController::class, 'upload']);
    Route::delete('/media/{media}', [ApplicationController::class, 'destroyMedia']);
    Route::post('application/{application}/status/{status}', [ApplicationController::class,'updateStatus']);
    Route::get('application/{application}/status', [ApplicationController::class,'viewStatus']);
    Route::get('/profile', [AuthController::class, 'profile']);
    Route::resource('status', StatusController::class);
    Route::get('application/{application}/getmedia', [ApplicationController::class, 'getMedia']);
    Route::post('application/download', [ApplicationController::class, 'downloadMedia']);
    Route::resource('application.message', MessageController::class)->shallow();
    Route::resource('application', ApplicationController::class);
    Route::resource('intake', IntakeController::class);
    Route::get('/course/search', [CourseController::class,'search']);
    Route::resource('level', LevelController::class);
    Route::resource('university', UniversityController::class);
});

Route::prefix('admin')->group(function () {
    Route::middleware(['auth:sanctum', 'role:admin'])->group(function () {
        Route::post('/document/{document}/upload', [DocumentController::class, 'upload']);
        Route::post('/document/{media}/rename', [DocumentController::class, 'renameMedia']);
        Route::get('/document/search', [DocumentController::class, 'search']);
        Route::resource('document', DocumentController::class);
        Route::get('/agent-request', [AuthController::class, 'agent']);
        Route::get('/agent-verified', [AuthController::class, 'agentVerified']);
        Route::post('/verify-agent/{id}', [AuthController::class, 'verify']);
        Route::delete('/delete-agent/{id}', [AuthController::class, 'deleteAgent']);
    });

    Route::middleware(['auth:sanctum'])->group(function () {
        Route::post('university/import', [UniversityController::class,'import']);
        Route::post('course/import', [CourseController::class,'import']);
        Route::resource('university.course', CourseController::class)->shallow();
    });
});
