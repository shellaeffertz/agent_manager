<?php

use Inertia\Inertia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use App\Http\Controllers\UserController;
use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\WorkspaceController;

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

Route::redirect('/', '/login');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {

    Route::get('/workspace', [WorkspaceController::class, 'index'])->name('workspace');
    Route::post('/task/store', [WorkspaceController::class, 'store'])->name('add-task');
    Route::post('/link/store', [WorkspaceController::class, 'storeVerificationLink'])->name('store-verification-link');
    Route::put('/task/update', [WorkspaceController::class, 'update'])->name('update-task');
    Route::delete('/task/destroy', [WorkspaceController::class, 'destroy'])->name('destroy-task');
    Route::put('/task/send', [WorkspaceController::class, 'send'])->name('send-task');
    Route::put('/task/reject', [WorkspaceController::class, 'reject'])->name('reject-task');
    Route::put('/task/finish', [WorkspaceController::class, 'finish'])->name('finish-task');
    Route::put('/link/verify', [WorkspaceController::class, 'verifyLink'])->name('verify-link');
    Route::put('/finish', [WorkspaceController::class, 'finishProject'])->name('finish-project');
    Route::post('/download', [WorkspaceController::class, 'downloadData'])->name('download-data');

    Route::middleware('admin')->group(function() {
        Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::post('/user/store', [UserController::class, 'store'])->name('store-user');
        Route::put('/user/update', [UserController::class, 'update'])->name('update-user');
        Route::delete('/user/destroy', [UserController::class, 'destroy'])->name('destroy-user');
    });

    Route::get('/t_redirect', function(){
        return Socialite::driver('telegram')->redirect();
    })->name('redirect-to-telegram-widget');

    Route::get('/t_callback', function (Request $request) {

        $telegramUser = Socialite::driver('telegram')->user();

        if(! $request->user()->telegram_chat_id) {
            $request->user()->update([
                'telegram_chat_id' => $telegramUser->id
            ]);
        }

        return redirect()->route('profile.show')->banner('Telegram connected successfully');

    })->name('telegram-callback');
});
