<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'facility_id' => 'required|exists:facilities,id',
            'user_id' => 'required|exists:users,id',
            'purpose' => 'required',
            'status' => 'required',
            'remarks' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'reservation_date' => 'required',
        ]);
        $reservation = \App\Models\Reservation::where('facility_id', $request->facility_id)
            ->where('reservation_date', $request->reservation_date)
            ->where('start_time', '=', $request->start_time)
            ->get();

        if ($reservation->count() > 0) {
            return response()->json(['message' => 'Facility is already booked for the given time'], 422);
        }
        $reservation = \App\Models\Reservation::create($request->all());
        return response()->json($reservation, 201);
    }
}
