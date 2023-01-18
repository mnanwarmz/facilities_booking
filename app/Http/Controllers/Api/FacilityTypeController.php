<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\FacilityTypeRequest;
use App\Models\FacilityType;
use Illuminate\Http\Request;

class FacilityTypeController extends Controller
{
    public function index()
    {
        return response()->json(['data' => FacilityType::latest()->get()], 200);
    }
    public function store(FacilityTypeRequest $request)
    {
        $facilityType = FacilityType::create($request->all());
        return response()->json($facilityType, 201);
    }

    public function show($id)
    {
        $facilityType = FacilityType::findOrFail($id);
        return response()->json(['data' => $facilityType], 200);
    }
    public function deactivate($id)
    {
        $facilityType = FacilityType::findOrFail($id);
        $facilityType->is_active = false;
        $facilityType->save();
        return response()->json(['data' => $facilityType], 200);
    }

    public function update($id, FacilityTypeRequest $request)
    {
        $facilityType = FacilityType::findOrFail($id);
        $facilityType->update($request->all());
        return response()->json(['data' => $facilityType], 200);
    }
}
