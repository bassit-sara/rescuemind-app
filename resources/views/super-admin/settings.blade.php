@extends('layouts.admin')
@section('title', 'ตั้งค่าระบบ')
@section('page-title')
    <x-heroicon-o-cog-6-tooth class="w-5 h-5 inline-block shrink-0" /> ตั้งค่าระบบส่วนกลาง (System Settings)
@endsection

@section('content')
<div class="max-w-4xl mx-auto">
    @if(session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center gap-2">
        <x-heroicon-o-check-circle class="w-5 h-5" />
        {{ session('success') }}
    </div>
    @endif

    <form action="{{ route('super-admin.settings.update') }}" method="POST">
        @csrf
        <div class="space-y-6">

            {{-- Status & Maintenance --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-gray-100 font-bold text-slate-800 flex items-center gap-2">
                    <x-heroicon-o-wrench-screwdriver class="w-5 h-5 text-indigo-600" />
                    สถานะระบบ (Status & Maintenance)
                </div>
                <div class="p-6 space-y-5">
                    @foreach($settings['status'] ?? [] as $setting)
                        @include('super-admin.partials.setting-input', ['setting' => $setting])
                    @endforeach
                </div>
            </div>

            {{-- Features --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-gray-100 font-bold text-slate-800 flex items-center gap-2">
                    <x-heroicon-o-sparkles class="w-5 h-5 text-indigo-600" />
                    ฟีเจอร์หลัก (Features)
                </div>
                <div class="p-6 space-y-5">
                    @foreach($settings['features'] ?? [] as $setting)
                        @include('super-admin.partials.setting-input', ['setting' => $setting])
                    @endforeach
                </div>
            </div>

            {{-- Notifications --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-gray-100 font-bold text-slate-800 flex items-center gap-2">
                    <x-heroicon-o-bell class="w-5 h-5 text-indigo-600" />
                    การแจ้งเตือน (Notifications)
                </div>
                <div class="p-6 space-y-5">
                    @foreach($settings['notifications'] ?? [] as $setting)
                        @include('super-admin.partials.setting-input', ['setting' => $setting])
                    @endforeach
                </div>
            </div>

            {{-- System --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="bg-slate-50 px-6 py-4 border-b border-gray-100 font-bold text-slate-800 flex items-center gap-2">
                    <x-heroicon-o-computer-desktop class="w-5 h-5 text-indigo-600" />
                    ค่าคงที่ระบบ (System Variables)
                </div>
                <div class="p-6 space-y-5">
                    @foreach($settings['system'] ?? [] as $setting)
                        @include('super-admin.partials.setting-input', ['setting' => $setting])
                    @endforeach
                </div>
            </div>

        </div>

        <div class="mt-8 flex justify-end">
            <button type="submit" class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-bold shadow-sm hover:bg-indigo-700 transition-colors flex items-center gap-2">
                <x-heroicon-o-check class="w-5 h-5" /> บันทึกการตั้งค่า
            </button>
        </div>
    </form>
</div>
@endsection
