<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SuperAdminMt3Controller extends Controller
{
    public function homeRecovery()
    {
        return view('super-admin.mt3.home-recovery');
    }

    public function communityNeeds()
    {
        return view('super-admin.mt3.community-needs');
    }

    public function volunteer()
    {
        return view('super-admin.mt3.volunteer');
    }

    public function donation()
    {
        return view('super-admin.mt3.donation');
    }

    public function livelihood()
    {
        return view('super-admin.mt3.livelihood');
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

        return view('super-admin.mt3.mental-recovery', compact('assessments', 'appointments', 'moods', 'journals'));
    }
}
