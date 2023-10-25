<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utilities\Date;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(5);

        return $users;
    }

    public function search(Request $request)
    {
        return User::where('name', 'like', '%' . $request['query'] .  '%')->paginate(5);
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

    public function changeRole(Request $request ,User $user)
    {
        $user->update([
            'role' => $request->role
        ]);

        return $user;
    }
}
