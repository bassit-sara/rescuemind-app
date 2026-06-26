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

    public function needsTracking()
    {
        $latestNeed = CommunityNeed::latest()->first();
        return view('mt3.needs-tracking', compact('latestNeed'));
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

    public function livelihoodTracking()
    {
        $latestLivelihood = null;
        if (auth()->check()) {
            $latestLivelihood = \App\Models\Mt3Livelihood::where('user_id', auth()->id())->latest()->first();
        }
        
        if (!$latestLivelihood) {
            return redirect()->route('mt3.livelihood')->with('error', 'ไม่พบข้อมูลการแจ้งขอฟื้นฟูอาชีพ กรุณาส่งคำขอก่อน');
        }

        return view('mt3.livelihood-tracking', compact('latestLivelihood'));
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
        $recovery->phone = $request->input('phone');
        
        // Handle checkboxes/boolean needs
        $needs = $request->input('needs', []);
        $recovery->need_cleaning = in_array('cleaning', $needs) ? true : false;
        $recovery->need_electric = in_array('electric', $needs) ? true : false;
        $recovery->need_plumbing = in_array('plumbing', $needs) ? true : false;
        $recovery->need_structure = in_array('structure', $needs) ? true : false;
        
        $recovery->additional_details = $request->input('details');
        $recovery->save();

        // Broadcast the event for real-time admin update
        broadcast(new \App\Events\HomeRecoveryRequested($recovery));

        return response()->json(['success' => true, 'message' => 'บันทึกข้อมูลเรียบร้อยแล้ว', 'id' => $recovery->id]);
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

    public function storeVolunteer(Request $request)
    {
        $volunteer = new \App\Models\Volunteer();
        $volunteer->user_id = auth()->id() ?? 1;
        $volunteer->name = $request->input('name');
        $volunteer->phone = $request->input('phone');
        $volunteer->volunteer_type = $request->input('volunteer_type');
        $volunteer->duration_days = $request->input('duration_days');
        $volunteer->province = $request->input('province', 'ไม่ระบุ');
        $volunteer->skills = json_encode($request->input('skills', []));
        $volunteer->is_available = true;
        $volunteer->status = 'active';
        $volunteer->approval_status = 'pending';
        $volunteer->save();

        return response()->json(['success' => true, 'message' => 'ลงทะเบียนอาสาสมัครสำเร็จ']);
    }

    public function storeDonation(Request $request)
    {
        $donation = new \App\Models\Mt3Donation();
        $donation->donor = $request->input('donor');
        $donation->phone = $request->input('phone');
        $donation->items = $request->input('items');
        $donation->tracking_no = $request->input('tracking_no');
        $donation->location = 'ศูนย์ประสานงาน กทม.'; // Default location since user doesn't pick it
        $donation->save();

        return back()->with('success', 'บันทึกข้อมูลการบริจาคเรียบร้อยแล้ว ขอบคุณสำหรับน้ำใจของคุณ');
    }

    public function storeLivelihood(Request $request)
    {
        $livelihood = new \App\Models\Mt3Livelihood();
        $livelihood->user_id = auth()->id();
        $livelihood->business_type = $request->input('business_type');
        $livelihood->business_type_other = $request->input('business_type_other');
        $livelihood->location = $request->input('location');
        $livelihood->damage_details = $request->input('damage_details');
        $livelihood->damage_value = $request->input('damage_value');
        $livelihood->needs = $request->input('needs', []); // Array of checked needs
        $livelihood->save();

        return back()->with('success', 'บันทึกข้อมูลแจ้งความเสียหายเรียบร้อยแล้ว');
    }

    public function submitForm(Request $request)
    {
        // Mock submission success for Hackathon
        return back()->with('success', 'บันทึกข้อมูลเรียบร้อยแล้ว ขอบคุณสำหรับข้อมูลของคุณ');
    }
}
