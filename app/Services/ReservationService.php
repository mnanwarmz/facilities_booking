<?php

namespace App\Services;

use App\Models\Reservation;

class ReservationService
{
    public function checkIfFacilityIsAvailable($facilityId, $reservationDate, $startTime)
    {
        $reservation = Reservation::where('facility_id', $facilityId)
            ->where('reservation_date', $reservationDate)
            ->where('start_time', '=', $startTime)
            ->get();

        if ($reservation->count() > 0) {
            return false;
        }
        return true;
    }

    public function getReservationsBelongingToUser($userId)
    {
        $reservations = Reservation::where('user_id', $userId)->latest()->get();
        return $reservations;
    }
}
