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
            'name' => ['required'],
            'email' => ['required', 'unique:users,email'],
            'password' => ['required', 'min:8'],
        ]);

        return User::create($request->all());
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required'],
            'email' => ['required', 'unique:users,email,' . $user->id],
            'password' => ['sometimes', 'min:8'],
        ]);

        $user->update($request->all());

        return $user;
    }

    public function delete(User $user)
    {
        $user->delete();

        return response()->noContent();
    }
}
