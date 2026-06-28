<?php

use Illuminate\Support\Facades\Route;

// Backend chỉ phục vụ API — Nuxt frontend chạy riêng
Route::get('/', fn() => response()->json(['message' => 'API only']));
