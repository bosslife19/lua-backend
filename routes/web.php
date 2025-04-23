<?php

use App\Http\Controllers\ChatController;
use App\Models\User;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $users = User::where('id','!=', 2)->get();
    $admin = User::where('id', 2)->first();
    return view('welcome', ['users'=>$users, 'admin'=>$admin]);
});

Route::get('/user/{id}', [ChatController::class, 'showChat']);

