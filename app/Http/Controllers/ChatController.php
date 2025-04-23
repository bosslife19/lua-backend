<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function showChat($id)
{
    $user = User::findOrFail($id);
    $admin = User::where('id', 2)->first();
    return view('chat', compact('user', 'admin'));
}

}
