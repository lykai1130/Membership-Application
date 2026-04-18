<?php

use App\Http\Controllers\MemberController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'member-menu');
Route::view('/registration', 'registration');
Route::post('/registration', [MemberController::class, 'save']);
Route::get('/member-list', [MemberController::class, 'index']);
