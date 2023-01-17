<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRequest;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        // return json response

        return response()->json([
            'users' => \App\Models\User::all()
        ], 200);
    }

    public function store(UserRequest $request)
    {
        // create user
        $user = \App\Models\User::create($request->all());

        return response()->json([
            'message' => 'User created successfully'
        ], 201);
    }

    public function assignRoles(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'role_ids' => 'required|array',
            'role_ids.*' => 'exists:roles,id',
        ]);

        $user = \App\Models\User::find($request->user_id);
        $user->roles()->attach($request->role_ids);

        return response()->json([
            'message' => 'Roles assigned successfully'
        ], 200);
    }
}
