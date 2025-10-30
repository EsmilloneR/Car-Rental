<?php

namespace App\Http\Controllers;

use App\Events\GpsEvent;
use App\Models\Vehicle;
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

        $vehicle = Vehicle::with('manufacturer')->find($data['vehicle_id']);

        if (!$vehicle) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vehicle not found!',
            ], 404);
        }

        // Build GPS data payload (no saving to DB)
        $location = [
            'vehicle_id' => $vehicle->id,
            'vehicle_name' => $vehicle->manufacturer->brand ?? 'Unknown Brand',
            'manufacturer_model' => $vehicle->model ?? 'Unknown Model',
            'latitude' => $data['latitude'],
            'longitude' => $data['longitude'],
            'speed' => $data['speed'] ?? 0,
        ];

        // Broadcast to Reverb
        broadcast(new GpsEvent($location))->toOthers();

        return response()->json([
            'success' => true,
            'message' => 'GPS update broadcasted successfully!',
            'location' => $location,
        ]);
    }
}
