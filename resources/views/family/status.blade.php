@extends('layouts.app')
@section('title', 'ตรวจสอบความปลอดภัย')
@section('page-title')
    <x-heroicon-o-user-group class="w-5 h-5 inline-block shrink-0" /> Family Safety Check
@endsection

@section('content')
<div class="max-w-4xl mx-auto space-y-6">
    {{-- My Status --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h2 class="text-xl font-bold text-gray-800 mb-4">สถานะของฉัน</h2>
        <div class="flex items-center gap-4">
            <div class="w-16 h-16 rounded-2xl flex items-center justify-center {{ auth()->user()->is_safe ? 'bg-green-100' : 'bg-yellow-100' }}">
                @if(auth()->user()->is_safe)
                    <x-heroicon-s-check-circle class="w-10 h-10 text-green-600" />
                @else
                    <x-heroicon-s-question-mark-circle class="w-10 h-10 text-yellow-600" />
                @endif
            </div>
            <div class="flex-1">
                <div class="font-semibold text-lg text-gray-800">
                    {{ auth()->user()->is_safe ? 'คุณแจ้งว่าปลอดภัยแล้ว' : 'ยังไม่ได้แจ้งสถานะ' }}
                </div>
                @if(auth()->user()->is_safe && auth()->user()->safe_at)
                <div class="text-sm text-gray-500 mt-1">
                    แจ้งเมื่อ {{ auth()->user()->safe_at->format('d/m/Y H:i') }} น.
                </div>
                @endif
            </div>
            <form action="{{ route('family.safe') }}" method="POST">
                @csrf
                <button type="submit" class="px-6 py-3 bg-gradient-to-r from-green-500 to-emerald-600 text-white rounded-xl hover:from-green-600 hover:to-emerald-700 transition-all shadow-lg font-semibold text-sm flex items-center gap-2">
                    <x-heroicon-o-check-circle class="w-5 h-5 shrink-0" /> {{ auth()->user()->is_safe ? 'ยืนยันอีกครั้ง' : 'ฉันปลอดภัย' }}
                </button>
            </form>
        </div>
    </div>

    {{-- How it works --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4">ระบบ Family Safety Check ทำงานอย่างไร</h3>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="text-center p-4">
                <div class="w-16 h-16 bg-green-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <x-heroicon-s-device-phone-mobile class="w-8 h-8 text-green-600" />
                </div>
                <h4 class="font-semibold text-gray-700 mb-1">1. กดยืนยัน</h4>
                <p class="text-sm text-gray-500">กดปุ่ม "ฉันปลอดภัย" เมื่อคุณอยู่ในสถานะปลอดภัย</p>
            </div>
            <div class="text-center p-4">
                <div class="w-16 h-16 bg-blue-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <x-heroicon-s-bell-alert class="w-8 h-8 text-blue-600" />
                </div>
                <h4 class="font-semibold text-gray-700 mb-1">2. แจ้งครอบครัว</h4>
                <p class="text-sm text-gray-500">ระบบจะแจ้งให้ครอบครัวของคุณทราบว่าคุณปลอดภัย</p>
            </div>
            <div class="text-center p-4">
                <div class="w-16 h-16 bg-purple-100 rounded-2xl flex items-center justify-center mx-auto mb-3">
                    <x-heroicon-s-heart class="w-8 h-8 text-purple-600" />
                </div>
                <h4 class="font-semibold text-gray-700 mb-1">3. สบายใจ</h4>
                <p class="text-sm text-gray-500">ลดความกังวลของคนที่คุณรัก ในสถานการณ์ภัยพิบัติ</p>
            </div>
        </div>
    </div>

    {{-- Emergency Contacts --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-4"><x-heroicon-o-phone class="w-5 h-5 inline-block mr-1 -mt-1" /> เบอร์โทรฉุกเฉิน</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <a href="tel:191" class="flex flex-col items-center p-4 bg-red-50 rounded-xl hover:bg-red-100 transition-colors">
                <span class="text-2xl font-bold text-red-600">191</span>
                <span class="text-xs text-red-500 mt-1">ตำรวจ</span>
            </a>
            <a href="tel:199" class="flex flex-col items-center p-4 bg-orange-50 rounded-xl hover:bg-orange-100 transition-colors">
                <span class="text-2xl font-bold text-orange-600">199</span>
                <span class="text-xs text-orange-500 mt-1">ดับเพลิง</span>
            </a>
            <a href="tel:1669" class="flex flex-col items-center p-4 bg-blue-50 rounded-xl hover:bg-blue-100 transition-colors">
                <span class="text-2xl font-bold text-blue-600">1669</span>
                <span class="text-xs text-blue-500 mt-1">การแพทย์ฉุกเฉิน</span>
            </a>
            <a href="tel:1323" class="flex flex-col items-center p-4 bg-green-50 rounded-xl hover:bg-green-100 transition-colors">
                <span class="text-2xl font-bold text-green-600">1323</span>
                <span class="text-xs text-green-500 mt-1">สุขภาพจิต</span>
            </a>
        </div>
    </div>
</div>
@endsection
