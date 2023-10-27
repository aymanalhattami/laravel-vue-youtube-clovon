<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Utilities\Date;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $users = User::query()
            ->when($request['query'], function($query, $search) {
                $query->where('name', 'like', '%' . $search .  '%');
            })
            ->latest()
            ->paginate(5);

        return $users;
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

    public function bulkDelete(Request $request)
    {
        User::whereIn('id', $request->ids)->delete();

        return response()->noContent();
    }

    public function stats()
    {
        $totalUsersCount = User::query()
            ->when(request('date_range') == 'today', function($query){
                return $query->whereBetween('created_at', [Carbon::now()->today(), Carbon::now()]);
            })
            ->when(request('date_range') == '30_days', function($query){
                return $query->whereBetween('created_at', [Carbon::now()->subDays(30), Carbon::now()]);
            })
            ->when(request('date_range') == '60_days', function($query){
                return $query->whereBetween('created_at', [Carbon::now()->subDays(60), Carbon::now()]);
            })
            ->when(request('date_range') == '360_days', function($query){
                return $query->whereBetween('created_at', [Carbon::now()->subDays(360), Carbon::now()]);
            })
            ->when(request('date_range') == 'month_to_date', function($query){
                return $query->whereBetween('created_at', [Carbon::now()->firstOfMonth(), Carbon::now()]);
            })
            ->when(request('date_range') == 'month_to_date', function($query){
                return $query->whereBetween('created_at', [Carbon::now()->firstOfYear(), Carbon::now()]);
            })
            ->count();

        return response()->json([
            'totalUsersCount' => $totalUsersCount
        ]);
    }
}
