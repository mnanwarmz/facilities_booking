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
}
