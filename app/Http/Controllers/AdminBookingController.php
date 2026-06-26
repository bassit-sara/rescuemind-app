<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ShelterBooking;
use Illuminate\Support\Str;

class AdminBookingController extends Controller
{
    public function index()
    {
        $bookings = ShelterBooking::with(['user', 'reliefPoint'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('admin.bookings.index', compact('bookings'));
    }

    public function update(Request $request, ShelterBooking $booking)
    {
        $request->validate([
            'status' => 'required|in:approved,rejected',
        ]);

        if ($request->status === 'approved' && $booking->status !== 'approved') {
            // Generate Room Key (e.g. RM-5A2F)
            $booking->room_key = 'RM-' . strtoupper(Str::random(4));
            
            // Increment current occupancy
            $reliefPoint = $booking->reliefPoint;
            $reliefPoint->current_occupancy += $booking->number_of_people;
            $reliefPoint->save();
        } elseif ($request->status === 'rejected' && $booking->status === 'approved') {
            // If it was previously approved and now rejected, revert occupancy
            $reliefPoint = $booking->reliefPoint;
            $reliefPoint->current_occupancy = max(0, $reliefPoint->current_occupancy - $booking->number_of_people);
            $reliefPoint->save();
            $booking->room_key = null;
        }

        $booking->status = $request->status;
        $booking->save();

        return redirect()->route('admin.bookings.index')->with('success', 'อัปเดตสถานะการจองเรียบร้อยแล้ว');
    }
}
