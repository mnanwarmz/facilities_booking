<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $reservation = \App\Models\Reservation::create($request->all());
        return response()->json($reservation, 201);
    }
}
