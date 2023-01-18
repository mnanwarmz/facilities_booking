<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FacilityTypeRequest;
use App\Models\FacilityType;
use Illuminate\Http\Request;

class FacilityTypeController extends Controller
{
    public function store(FacilityTypeRequest $request)
    {
        $facilityType = FacilityType::create($request->all());
        return response()->json($facilityType, 201);
    }
}
