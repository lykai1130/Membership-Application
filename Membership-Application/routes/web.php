<?php

use App\Http\Controllers\MemberController;
use App\Http\Controllers\RewardAchieverController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'member-menu');
Route::get('/registration', [MemberController::class, 'registration']);
Route::post('/registration', [MemberController::class, 'save']);
Route::get('/member-list', [MemberController::class, 'index']);
Route::get('/member-list/{member}/edit', [MemberController::class, 'edit']);
Route::put('/member-list/{member}', [MemberController::class, 'update']);
Route::get('/member-list/{member}/referral-tree', [MemberController::class, 'referralTree']);
Route::get('/reward-report', [RewardAchieverController::class, 'index']);
Route::get('/reward-report/export', [RewardAchieverController::class, 'export']);
