<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Events\VolunteerLocationUpdated;

class TrackingController extends Controller
{
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'sos_id' => 'required|exists:sos_requests,id',
        ]);

        $volunteerId = auth()->id() ?? 0;

        broadcast(new VolunteerLocationUpdated(
            $volunteerId,
            $request->sos_id,
            $request->latitude,
            $request->longitude
        ))->toOthers();

        return response()->json(['success' => true]);
    }
}
