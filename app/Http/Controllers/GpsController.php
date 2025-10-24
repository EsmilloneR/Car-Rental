<?php

namespace App\Http\Controllers;

use App\Events\GpsEvent;
use App\Models\Location;
use Illuminate\Http\Request;

class GpsController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->validate([
            'vehicle_id' => 'required|integer|exists:vehicles,id',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'speed' => 'nullable|numeric',
        ]);

        $location = Location::create($data);

        broadcast(new GpsEvent($location))->toOthers();


        return response()->json(['success' => true, 'location' => $location]);
    }
    
}
