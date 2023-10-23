<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $user = User::latest()->get();

        return $user;
    }

    public function store(Request $request)
    {
        $request->validate([
            'email' => ['required', 'unique:users,email']
        ]);

        return User::create($request->all());
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'email' => ['required', 'unique:users,email,' . $user->id]
        ]);

        $user->update($request->all());

        return $user;
    }
}
