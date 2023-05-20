<?php

use App\Http\Livewire\Chatbot;
use App\Http\Livewire\Dashboard;
use App\Http\Livewire\Documents;
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

Route::get('/', Dashboard::class)->name('dashboard');
Route::get('/documents', Documents::class)->name('documents.index');
Route::get('/documents/{document}', ShowDocument::class)->name('documents.show');
Route::get('/chatbot', Chatbot::class)->name('chatbot.index');
