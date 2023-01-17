<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
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
}
