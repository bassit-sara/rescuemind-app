<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FamilySafeController extends Controller
{
    public function index()
    {
        return view('family.status');
    }

    public function markSafe(Request $request)
    {
        auth()->user()->update([
            'is_safe' => true,
            'safe_at' => now(),
        ]);
        return back()->with('success', 'บันทึกสถานะปลอดภัยแล้ว ครอบครัวจะได้รับการแจ้งเตือน');
    }
}
