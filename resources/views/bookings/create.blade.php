@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto py-8 px-4">
    <div class="mb-6">
        <a href="{{ route('relief-points.index') }}" class="text-blue-600 hover:text-blue-800 flex items-center gap-2">
            <x-heroicon-s-arrow-left class="w-4 h-4" /> ย้อนกลับ
        </a>
    </div>

    <div class="bg-white rounded-3xl p-8 shadow-sm border border-gray-100">
        <div class="mb-8 text-center">
            <div class="w-16 h-16 bg-orange-100 text-orange-500 rounded-full flex items-center justify-center mx-auto mb-4">
                <x-heroicon-s-home-modern class="w-8 h-8" />
            </div>
            <h1 class="text-2xl font-bold text-gray-900">จองที่พักพิง</h1>
            <p class="text-gray-500 mt-2">{{ $reliefPoint->name }}</p>
        </div>

        @if ($errors->any())
            <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('bookings.store', $reliefPoint) }}" method="POST">
            @csrf
            
            <div class="space-y-6">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">จำนวนคนที่จะมาพัก (คน)</label>
                    <input type="number" name="number_of_people" min="1" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('number_of_people', 1) }}">
                </div>

                <div class="bg-gray-50 p-6 rounded-2xl border border-gray-100" id="address-section">
                    <h3 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                        <x-heroicon-s-map-pin class="w-5 h-5 text-blue-500" /> ที่อยู่ปัจจุบัน / จุดที่กำลังเดินทางมา
                    </h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">ตำบล/แขวง</label>
                            <input type="text" name="subdistrict" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('subdistrict') }}" placeholder="พิมพ์ชื่อตำบล...">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">อำเภอ/เขต</label>
                            <input type="text" name="district" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('district', auth()->user()->district) }}" placeholder="พิมพ์ชื่ออำเภอ...">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">จังหวัด</label>
                            <input type="text" name="province" id="province-input" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('province', auth()->user()->province) }}" placeholder="พิมพ์ชื่อจังหวัด...">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">บ้านเลขที่ / ถนน / ซอย</label>
                            <input type="text" name="house_no" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" value="{{ old('house_no') }}" placeholder="เช่น 123/4 ซอย 5 ถนน 6">
                        </div>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">สถานการณ์ ณ ตรงนั้น (บอกสถานการณ์เพื่อให้เจ้าหน้าที่ทราบ)</label>
                    <textarea name="current_situation" rows="2" required class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="เช่น น้ำท่วมสูงระดับเอว, การไฟฟ้าตัดไฟแล้ว, ออกจากบ้านไม่ได้">{{ old('current_situation') }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">ข้อมูลเพิ่มเติม (ถ้ามี)</label>
                    <textarea name="additional_info" rows="2" class="w-full rounded-xl border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="เช่น มีสัตว์เลี้ยงมาด้วย 1 ตัว, มีผู้สูงอายุ 1 ท่าน">{{ old('additional_info') }}</textarea>
                </div>
            </div>

            <div class="mt-8">
                <button type="submit" id="submit-btn" class="w-full py-3 bg-orange-500 text-white rounded-xl font-bold text-lg hover:bg-orange-600 hover:shadow-lg transition-all flex items-center justify-center gap-2">
                    <x-heroicon-s-check-circle class="w-6 h-6" /> ยืนยันการจอง
                </button>
            </div>
        </form>
    </div>
</div>

@push('styles')
    <link rel="stylesheet" href="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.css">
    <style>
        .tt-dataset { background-color: white; border-radius: 8px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1); border: 1px solid #f3f4f6; overflow: hidden; }
        .tt-suggestion { padding: 10px 15px; cursor: pointer; border-bottom: 1px solid #f3f4f6; font-size: 14px; }
        .tt-suggestion:hover, .tt-suggestion.tt-cursor { background-color: #eff6ff; color: #1d4ed8; font-weight: bold; }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/JQL.min.js"></script>
    <script src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dependencies/typeahead.bundle.js"></script>
    <script src="https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/dist/jquery.Thailand.min.js"></script>
    <script>
        $(function() {
            $.Thailand({
                database: 'https://earthchie.github.io/jquery.Thailand.js/jquery.Thailand.js/database/db.json',
                $district: $('[name="subdistrict"]'),
                $amphoe: $('[name="district"]'),
                $province: $('[name="province"]')
            });
        });
    </script>
@endpush

@endsection
