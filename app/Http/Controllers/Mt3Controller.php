<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\HomeRecovery;
use App\Models\CommunityNeed;

class Mt3Controller extends Controller
{
    public function homeRecovery()
    {
        return view('mt3.home-recovery');
    }

    public function communityNeeds()
    {
        return view('mt3.community-needs');
    }

    public function recoveryTracking()
    {
        $latestRecovery = HomeRecovery::latest()->first();
        return view('mt3.recovery-tracking', compact('latestRecovery'));
    }

    public function volunteer()
    {
        return view('mt3.volunteer');
    }

    public function donation()
    {
        return view('mt3.donation');
    }

    public function aiMatching()
    {
        return view('mt3.ai-matching');
    }

    public function aiCompanion()
    {
        return view('mt3.ai-companion');
    }

    public function livelihood()
    {
        return view('mt3.livelihood');
    }

    public function analytics()
    {
        return view('mt3.analytics');
    }

    public function storeHomeRecovery(Request $request)
    {
        $validated = $request->validate([
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
            // Allow other fields optionally
        ]);
        
        $recovery = new HomeRecovery();
        $recovery->user_id = auth()->id();
        $recovery->lat = $request->input('lat');
        $recovery->lng = $request->input('lng');
        $recovery->address = $request->input('address');
        $recovery->zip_code = $request->input('zipCode');
        $recovery->landmark = $request->input('landmark');
        
        // Handle checkboxes/boolean needs
        $needs = $request->input('needs', []);
        $recovery->need_cleaning = in_array('cleaning', $needs) ? true : false;
        $recovery->need_electric = in_array('electric', $needs) ? true : false;
        $recovery->need_plumbing = in_array('plumbing', $needs) ? true : false;
        $recovery->need_structure = in_array('structure', $needs) ? true : false;
        
        $recovery->additional_details = $request->input('details');
        $recovery->save();

        return response()->json(['success' => true, 'message' => 'บันทึกข้อมูลฟื้นฟูบ้านเรียบร้อยแล้ว', 'id' => $recovery->id]);
    }

    public function storeCommunityNeed(Request $request)
    {
        $validated = $request->validate([
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);
        
        $community = new CommunityNeed();
        $community->user_id = auth()->id();
        $community->community_name = $request->input('communityName');
        $community->population = $request->input('population');
        $community->zip_code = $request->input('zipCode');
        
        $needs = $request->input('needs', []);
        $community->food_sets = $needs['food'] ?? 0;
        $community->medicine_sets = $needs['medicine'] ?? 0;
        $community->cleaning_sets = $needs['cleaning'] ?? 0;
        $community->clothing_sets = $needs['clothing'] ?? 0;
        
        $community->lat = $request->input('lat');
        $community->lng = $request->input('lng');
        $community->save();

        return response()->json(['success' => true, 'message' => 'บันทึกข้อมูลความต้องการชุมชนเรียบร้อยแล้ว', 'id' => $community->id]);
    }

    public function submitForm(Request $request)
    {
        // Mock submission success for Hackathon
        return back()->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว ขอบคุณสำหรับข้อมูลของคุณ');
    }
}
