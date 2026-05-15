<?php

use App\Http\Controllers\ChatController;
use Illuminate\Support\Facades\Route;

Route::get('/', [ChatController::class, 'index'])->name('chat.index');

Route::get('/messages', [ChatController::class, 'getMessages'])->name('chat.messages');

Route::post('/send-message', [ChatController::class, 'sendMessage'])->name('chat.send');
