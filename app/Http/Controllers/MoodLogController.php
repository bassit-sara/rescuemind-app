<?php

namespace App\Http\Controllers;

use App\Models\MoodLog;
use App\Models\Journal;
use Illuminate\Http\Request;

class MoodLogController extends Controller
{
    public function index()
    {
        $moods = auth()->user()->moodLogs()->orderByDesc('logged_date')->take(30)->get();
        $todayMood = auth()->user()->moodLogs()->where('logged_date', today())->first();
        return view('mental.mood', compact('moods', 'todayMood'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'mood' => 'required|integer|min:1|max:5',
            'note' => 'nullable|string|max:500',
        ]);

        MoodLog::updateOrCreate(
            ['user_id' => auth()->id(), 'logged_date' => today()],
            ['mood' => $request->mood, 'note' => $request->note]
        );

        return back()->with('success', 'บันทึกอารมณ์วันนี้สำเร็จ');
    }

    public function update(Request $request, MoodLog $mood)
    {
        if ($mood->user_id !== auth()->id()) abort(403);
        
        if (!$mood->logged_date->isToday()) {
            return back()->with('error', 'ไม่สามารถแก้ไขบันทึกของวันก่อนหน้าได้');
        }

        $request->validate([
            'mood' => 'required|integer|min:1|max:5',
            'note' => 'nullable|string|max:500',
        ]);

        $mood->update([
            'mood' => $request->mood,
            'note' => $request->note
        ]);

        return back()->with('success', 'แก้ไขบันทึกอารมณ์สำเร็จ');
    }

    public function destroy(MoodLog $mood)
    {
        if ($mood->user_id !== auth()->id()) abort(403);
        
        if (!$mood->logged_date->isToday()) {
            return back()->with('error', 'ไม่สามารถลบบันทึกของวันก่อนหน้าได้');
        }
        
        $mood->delete();
        
        return back()->with('success', 'ลบบันทึกอารมณ์สำเร็จ');
    }
}
