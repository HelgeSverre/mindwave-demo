<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\SocialController;
use App\Http\Controllers\SignedStorageUrlController;
use App\Http\Livewire\Chatbot;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Documents;
use App\Http\Livewire\Emails;
use App\Http\Livewire\ShowDocument;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::post('vapor/signed-storage-url', [SignedStorageUrlController::class, 'store']);

Route::group(['middleware' => 'auth'], function () {

    Route::get('/', Dashboard::class)->name('dashboard');
    Route::get('/emails', Emails::class)->name('emails.index');
    Route::get('/documents', Documents::class)->name('documents.index');
    Route::get('/documents/{document}', ShowDocument::class)->name('documents.show');
    Route::get('/chatbot', Chatbot::class)->name('chatbot.index');
});

Route::get('/login', [LoginController::class, 'show'])->name('login');
Route::get('/auth/google', [SocialController::class, 'redirectToGoogle'])->name('auth.google.redirect');
Route::get('/auth/google/callback', [SocialController::class, 'handleGoogleCallback'])->name('auth.google.callback');
