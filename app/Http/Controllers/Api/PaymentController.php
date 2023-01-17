<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'method' => 'required',
            'amount' => 'required|numeric',
            'reservation_id' => 'required|exists:reservations,id',
        ]);

        $payment = \App\Models\Payment::create($request->all());

        return response()->json($payment, 201);
    }
}
