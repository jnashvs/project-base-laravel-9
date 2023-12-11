<?php

use App\Http\Controllers\Backoffice\Admin\Index;
use App\Http\Controllers\Backoffice\Admin\ProfileController;
use App\Http\Controllers\Backoffice\Admin\UserController;
use App\Http\Controllers\Backoffice\Admin\FileManager\FileTypesController;
use App\Http\Controllers\Backoffice\Admin\FileManager\FilesController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Add this ' ->middleware('verified') ' to protect the route with only verified users;

Route::group(['namespace' => 'App\Http\Controllers\Backoffice',], function () {
    Auth::routes();
});

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/adminlayout', function () {
    return view('backoffice.layouts.admin');
});
Route::get('/dash', function () {
    return view('backoffice.admin.index');
});

//Route::post('login', 'Backoffice\Auth\LoginController@login');

Route::group([
    'middleware' => ['auth', 'role:SuperAdmin|Admin|Manager', 'permission:AdminPanel access'],
    'namespace' => 'App\Http\Controllers\Backoffice\Admin',
    'prefix' => 'admin',
    'as' => 'admin.'
], function () {
    Route::get('/', [Index::class, 'index'])->name('dashboard');

    Route::resource('profile', 'ProfileController');
    Route::patch('profile/{user}/passUpdate', [ProfileController::class, 'passUpdate'])->name('profile.passUpdate');
    Route::patch('profile/{user}/othersUpdate', [ProfileController::class, 'othersUpdate'])->name('profile.othersUpdate');

    Route::resource('roles', 'RoleController');
    Route::resource('permissions', 'PermissionController');

    Route::resource('users', 'UserController');
    Route::patch('users/{user}/passUpdate', [UserController::class, 'passUpdate'])->name('users.passUpdate');
    Route::patch('users/{user}/othersUpdate', [UserController::class, 'othersUpdate'])->name('users.othersUpdate');

    /* Email Verification Links */
    Route::get('/email/verify', function () {
        return view('auth.verify');
    })->middleware('auth')->name('verification.notice');

    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('success', 'Verification link sent!');
    })->middleware(['auth', 'throttle:6,1'])->name('verification.resend');

    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect('/home');
    })->middleware(['auth', 'signed'])->name('verification.verify');

    Route::controller(FilesController::class)->group(function () {
        Route::get('/files', 'index')->name('files');
    });

    Route::controller(FileTypesController::class)->group(function () {
        Route::get('/file-types', 'index')->name('file-types');
        Route::get('/file-types/edit/{id?}/', 'edit')->name('edit-file-types');
        // Route::post('/file-types/store/{id?}', 'store')->name('file.types.store');
        Route::post('/file-types/create', 'create')->name('file.types.create');
        Route::post('/file-types/save/{id?}', 'save')->name('filetypes.save');
        Route::delete('/file-types/delete/', 'delete')->name('delete-file-types');
        Route::get('/file-types/show', 'show')->name('filetypes.show');
    });

});

