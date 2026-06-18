<?php

namespace App\Http\Controllers;

use App\Models\MissingPerson;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MissingPersonController extends Controller
{
    public function index()
    {
        $missingPersons = MissingPerson::with('reporter')
            ->whereIn('status', ['missing', 'searching'])
            ->latest()
            ->paginate(12);
        return view('missing-persons.index', compact('missingPersons'));
    }

    public function create()
    {
        return view('missing-persons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'          => 'required|string|max:255',
            'age'           => 'nullable|integer|min:0|max:120',
            'gender'        => 'nullable|in:male,female,other',
            'province'      => 'nullable|string|max:100',
            'last_seen_lat' => 'nullable|numeric',
            'last_seen_lng' => 'nullable|numeric',
            'last_seen_at'  => 'nullable|date',
            'description'   => 'nullable|string|max:1000',
            'photo'         => 'nullable|image|max:5120',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('missing-persons', 'public');
        }

        $validated['reporter_id'] = auth()->id();
        $validated['status'] = 'missing';

        MissingPerson::create($validated);

        return redirect()->route('missing-persons.index')->with('success', 'แจ้งคนหายสำเร็จ');
    }

    public function show(MissingPerson $missingPerson)
    {
        return view('missing-persons.show', compact('missingPerson'));
    }

    public function officerIndex()
    {
        $missingPeople = MissingPerson::with('reporter')->latest()->paginate(20);
        return view('officer.missing.index', compact('missingPeople'));
    }

    public function updateStatus(Request $request, MissingPerson $missingPerson)
    {
        $request->validate(['status' => 'required|in:missing,searching,found,safe']);
        $missingPerson->update(['status' => $request->status]);
        return back()->with('success', 'อัปเดตสถานะสำเร็จ');
    }
}
