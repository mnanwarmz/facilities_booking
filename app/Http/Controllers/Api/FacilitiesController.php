<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilitiesController extends Controller
{
    public function index()
    {
        $facilities = Facility::latest()->get();
        return response()->json([
            'data' => $facilities->toArray()
        ]);
    }

    public function store(Request $request)
    {
        $facility = Facility::create($request->all());
        return response()->json([
            'data' => $facility->toArray()
        ]);
    }

    public function update($id, Request $request)
    {
        $facility = Facility::findOrFail($id);
        $facility->update($request->all());
        return response()->json([
            'data' => $facility->toArray()
        ]);
    }

    public function destroy($id)
    {
        $facility = Facility::findOrFail($id);
        $facility->delete();
        return response()->json([
            'data' => $facility->toArray()
        ]);
    }
}
