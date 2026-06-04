<?php

use App\Http\Controllers\Backend\AuthController;
use App\Http\Controllers\Backend\DashboardController;
use App\Http\Controllers\Backend\SettingsController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware('guest')->group(function () {
    Route::get('/', [AuthController::class, 'loginGet'])->name('login.get');
    Route::post('/', [AuthController::class, 'loginPost'])->name('login.post');
});
/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::post('/logout', [AuthController::class, 'destroy'])->name('logout');

    Route::resource('users', UserController::class);
    Route::resource('roles', RoleController::class);



    Route::prefix('settings')->name('settings.')->group(function () {
 
        // General (identity + appearance)
        Route::get('general',        [SettingsController::class, 'general'])->name('general');
        Route::put('general',        [SettingsController::class, 'updateGeneral'])->name('general.update');
    
        // SEO
        Route::get('seo',            [SettingsController::class, 'seo'])->name('seo');
        Route::put('seo',            [SettingsController::class, 'updateSeo'])->name('seo.update');
    
        // Social
        Route::get('social',         [SettingsController::class, 'social'])->name('social');
        Route::put('social',         [SettingsController::class, 'updateSocial'])->name('social.update');
    
        // Delete individual file (AJAX or form DELETE)
        Route::delete('file/{key}',  [SettingsController::class, 'deleteFile'])->name('file.delete');
    
        // Redirect /settings → /settings/general
        Route::redirect('/', 'settings/general');
    });

});