<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuperAdminMt3Controller extends Controller
{
    public function homeRecovery()
    {
        $recoveries = \App\Models\HomeRecovery::with('user')->latest()->get();
        return view('super-admin.mt3.home-recovery', compact('recoveries'));
    }

    public function updateHomeRecovery(Request $request, $id)
    {
        $recovery = \App\Models\HomeRecovery::findOrFail($id);
        $request->validate([
            'status' => 'required|string',
        ]);

        $statusMap = [
            'รับคำขอความช่วยเหลือ' => 'pending',
            'จับคู่ทีมอาสาสมัคร' => 'matching',
            'ทีมอาสาสมัครกำลังลงพื้นที่' => 'in_progress',
            'การฟื้นฟูเสร็จสมบูรณ์' => 'completed',
        ];

        $recovery->update([
            'phone' => $request->phone,
            'additional_details' => $request->details,
            'zip_code' => $request->province,
            'status' => $statusMap[$request->status] ?? 'pending',
        ]);
        
        if ($request->name && $recovery->user) {
            $recovery->user->update(['name' => $request->name]);
        }

        return response()->json(['success' => true]);
    }

    public function destroyHomeRecovery($id)
    {
        \App\Models\HomeRecovery::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function communityNeeds()
    {
        $needs = \App\Models\CommunityNeed::with('user')->latest()->get();
        return view('super-admin.mt3.community-needs', compact('needs'));
    }

    public function updateCommunityNeed(Request $request, $id)
    {
        $need = \App\Models\CommunityNeed::findOrFail($id);
        $request->validate([
            'status' => 'required|string',
            'progress' => 'required|string',
        ]);

        $statusMap = [
            'วิกฤต' => 'critical',
            'เร่งด่วน' => 'urgent',
            'ปานกลาง' => 'moderate',
        ];

        $progressMap = [
            'รับข้อมูลประเมิน' => 'pending',
            'ตรวจสอบและจัดสรร' => 'verifying',
            'กำลังจัดส่ง' => 'delivering',
            'ได้รับความช่วยเหลือแล้ว' => 'completed',
        ];

        $need->update([
            'community_name' => $request->community_name,
            'population' => $request->population,
            'zip_code' => $request->zip_code,
            'status' => $statusMap[$request->status] ?? 'pending',
            'progress' => $progressMap[$request->progress] ?? 'pending',
        ]);

        return response()->json(['success' => true]);
    }

    public function destroyCommunityNeed($id)
    {
        \App\Models\CommunityNeed::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function volunteer()
    {
        $volunteers = \App\Models\Volunteer::with('user')->latest()->get();
        return view('super-admin.mt3.volunteer', compact('volunteers'));
    }

    public function updateVolunteerApproval(Request $request, \App\Models\Volunteer $volunteer)
    {
        $request->validate([
            'approval_status' => 'required|in:pending,approved,rejected'
        ]);

        $volunteer->update([
            'approval_status' => $request->approval_status
        ]);

        if ($request->approval_status === 'approved' && $volunteer->user) {
            $volunteer->user->notify(new \App\Notifications\VolunteerApproved());
        }

        return back()->with('success', 'อัปเดตสถานะการอนุมัติสำเร็จ');
    }

    public function donation()
    {
        $donations = \App\Models\Mt3Donation::latest()->get();
        return view('super-admin.mt3.donation', compact('donations'));
    }

    public function livelihood()
    {
        $livelihoods = \App\Models\Mt3Livelihood::with('user')->latest()->get();
        return view('super-admin.mt3.livelihood', compact('livelihoods'));
    }

    public function updateLivelihood(Request $request, $id)
    {
        $livelihood = \App\Models\Mt3Livelihood::findOrFail($id);
        $request->validate([
            'status' => 'required|string',
        ]);

        $livelihood->update([
            'status' => $request->status,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroyLivelihood($id)
    {
        \App\Models\Mt3Livelihood::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    public function aiMatching()
    {
        return view('super-admin.mt3.ai-matching');
    }

    public function analytics()
    {
        return view('super-admin.mt3.analytics');
    }

    public function mentalRecovery()
    {
        $assessments = \App\Models\MentalAssessment::with('user')->latest()->get();
        $appointments = \App\Models\Appointment::with('user')->latest()->get();
        $moods = \App\Models\MoodLog::with('user')->latest()->get();
        $journals = \App\Models\Journal::with('user')->latest()->get();
        $mentalArticles = \App\Models\MentalArticle::latest()->get();

        return view('super-admin.mt3.mental-recovery', compact('assessments', 'appointments', 'moods', 'journals', 'mentalArticles'));
    }

    public function storeMentalAssessment(Request $request)
    {
        $request->validate([
            'user_name' => 'required|string',
            'score' => 'required|string',
            'severity' => 'required|string',
            'date' => 'required|date',
            'topic' => 'nullable|string',
        ]);

        $assessment = \App\Models\MentalAssessment::create([
            'user_id' => auth()->id(), // default to admin's ID
            'type' => 'manual',
            'score' => 0,
            'severity' => $request->severity,
            'answers' => ['manual_entry_name' => $request->user_name, 'manual_score' => $request->score, 'manual_date' => $request->date, 'topic' => $request->topic ?? 'mental'],
        ]);

        return response()->json(['success' => true, 'id' => $assessment->id]);
    }

    public function updateMentalAssessment(Request $request, $id)
    {
        $assessment = \App\Models\MentalAssessment::findOrFail($id);
        
        $answers = $assessment->answers ?? [];
        $answers['manual_entry_name'] = $request->user_name;
        $answers['manual_score'] = $request->score;
        $answers['manual_date'] = $request->date;
        $answers['topic'] = $request->topic ?? 'mental';

        $assessment->update([
            'severity' => $request->severity,
            'answers' => $answers,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroyMentalAssessment($id)
    {
        \App\Models\MentalAssessment::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }

    // --- Dynamic Assessment Form Builder ---

    public function mentalForms()
    {
        $forms = \App\Models\CustomAssessment::latest()->get();
        return view('super-admin.mt3.mental-forms', compact('forms'));
    }

    public function storeMentalForm(Request $request)
    {
        $request->validate([
            'slug' => 'required|string|unique:custom_assessments,slug',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'time_estimate' => 'nullable|string',
            'icon' => 'nullable|string',
            'color_theme' => 'nullable|string',
            'questions' => 'required|array',
        ]);

        $form = \App\Models\CustomAssessment::create([
            'slug'          => $request->slug,
            'category'      => $request->category ?? 'mental',
            'title'         => $request->title,
            'description'   => $request->description,
            'time_estimate' => $request->time_estimate,
            'icon'          => $request->icon ?? 'o-clipboard-document-check',
            'color_theme'   => $request->color_theme ?? 'indigo',
            'questions'     => $request->questions,
            'is_active'     => $request->has('is_active') ? $request->is_active : true,
        ]);

        return response()->json(['success' => true, 'id' => $form->id]);
    }

    public function updateMentalForm(Request $request, $id)
    {
        $form = \App\Models\CustomAssessment::findOrFail($id);

        $request->validate([
            'slug' => 'required|string|unique:custom_assessments,slug,' . $id,
            'title' => 'required|string',
            'description' => 'nullable|string',
            'time_estimate' => 'nullable|string',
            'icon' => 'nullable|string',
            'color_theme' => 'nullable|string',
            'questions' => 'required|array',
        ]);

        $form->update([
            'slug'          => $request->slug,
            'category'      => $request->category ?? 'mental',
            'title'         => $request->title,
            'description'   => $request->description,
            'time_estimate' => $request->time_estimate,
            'icon'          => $request->icon ?? 'o-clipboard-document-check',
            'color_theme'   => $request->color_theme ?? 'indigo',
            'questions'     => $request->questions,
            'is_active'     => $request->has('is_active') ? filter_var($request->is_active, FILTER_VALIDATE_BOOLEAN) : true,
        ]);

        return response()->json(['success' => true]);
    }

    public function destroyMentalForm($id)
    {
        \App\Models\CustomAssessment::findOrFail($id)->delete();
        return response()->json(['success' => true]);
    }
}
