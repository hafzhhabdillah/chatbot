<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ChatController;

// Route untuk menampilkan halaman utama chat
Route::get('/', [ChatController::class, 'index']);

// Route untuk memproses pesan chat dari JavaScript
Route::post('/chat', [ChatController::class, 'send']);
