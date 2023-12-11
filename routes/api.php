<?php

use App\Http\Controllers\Backoffice\Admin\FileManager\FilesController;
use App\Http\Controllers\Backoffice\Admin\FileManager\FileTypesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group([
    'namespace' => 'App\Http\Controllers\Backoffice\Admin',
], function () {

    Route::controller(FilesController::class)->group(function () {
        Route::get('/files', 'index')->name('files');
        Route::post('/files/upload', 'fileStore')->name('uploadFiles');
        Route::get('/files/all', 'allFiles')->name('allFiles');
        Route::delete('/files/remove/{id}', 'removeFile')->name('removeFile');
    });

    Route::controller(FileTypesController::class)->group(function () {
        Route::post('/file-types/create', 'create')->name('api.filetypes.create');
        Route::post('/file-types/update/{id}', 'update')->name('api.filetypes.update');
        Route::get('/file-types/all', 'getAll')->name('file-types-all');
        Route::get('/file-types/{id}', 'getFileType')->name('file-type-find');
    });
});