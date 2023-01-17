<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationRequest;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function store(ReservationRequest $request)
    {
        $reservationService = new \App\Services\ReservationService();
        $isAvailable = $reservationService->checkIfFacilityIsAvailable(
            $request->facility_id,
            $request->reservation_date,
            $request->start_time
        );
        if (!$isAvailable) {
            return response()->json(['message' => 'Facility is already booked for the given time'], 422);
        }
        $reservation = \App\Models\Reservation::create($request->all());
        return response()->json(['data' => $reservation], 201);
    }

    public function index()
    {
        $reservations = \App\Models\Reservation::latest()->get();
        return response()->json(['data' => $reservations], 200);
    }

    public function show($id)
    {
        $reservation = \App\Models\Reservation::findOrFail($id);
        // dd($reservation);
        if ($reservation) {
            return response()->json($reservation, 200);
        }
        return response()->json(['message' => 'Reservation not found'], 404);
    }

    public function showUserReservations()
    {
        $reservationService = new \App\Services\ReservationService();
        $reservations = $reservationService->getReservationsBelongingToUser(auth()->user()->id);
        return response()->json(['data' => $reservations], 200);
    }
}
