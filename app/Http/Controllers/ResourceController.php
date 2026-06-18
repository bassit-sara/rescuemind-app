<?php

namespace App\Http\Controllers;

use App\Models\Resource;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function index()
    {
        $resources = Resource::where('is_active', true)->orderBy('type')->paginate(20);
        return view('admin.resources.index', compact('resources'));
    }

    public function create()
    {
        return view('admin.resources.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'               => 'required|string|max:255',
            'type'               => 'required|in:boat,truck,medicine,food,water,other',
            'province'           => 'nullable|string|max:100',
            'location'           => 'nullable|string|max:255',
            'quantity'           => 'required|integer|min:0',
            'available_quantity' => 'required|integer|min:0',
            'unit'               => 'nullable|string|max:50',
        ]);
        Resource::create($validated);
        return redirect()->route('admin.resources.index')->with('success', 'เพิ่มทรัพยากรสำเร็จ');
    }

    public function edit(Resource $resource)
    {
        return view('admin.resources.edit', compact('resource'));
    }

    public function update(Request $request, Resource $resource)
    {
        $resource->update($request->only(['name','type','province','location','quantity','available_quantity','unit','is_active']));
        return back()->with('success', 'อัปเดตทรัพยากรสำเร็จ');
    }

    public function destroy(Resource $resource)
    {
        $resource->update(['is_active' => false]);
        return back()->with('success', 'ลบทรัพยากรสำเร็จ');
    }
}
