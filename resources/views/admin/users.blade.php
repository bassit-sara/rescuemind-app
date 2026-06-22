@extends('layouts.admin')
@section('title', 'จัดการผู้ใช้')
@section('page-title')
    <x-heroicon-o-users class="w-5 h-5 inline-block shrink-0" /> จัดการผู้ใช้
@endsection
@section('content')

{{-- Search --}}
<div class="bg-white rounded-2xl p-4 shadow-sm border border-gray-100 mb-6">
    <form method="GET" class="flex gap-3">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="ค้นหาชื่อหรืออีเมล..."
               class="flex-1 px-4 py-2.5 border border-gray-300 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500">
        <select name="role" class="px-3 py-2.5 border border-gray-300 rounded-xl text-sm">
            <option value="">ทุก Role</option>
            @foreach($roles as $role)
            <option value="{{ $role->name }}" {{ request('role') == $role->name ? 'selected' : '' }}>{{ $role->name }}</option>
            @endforeach
        </select>
        <button type="submit" class="px-5 py-2 bg-indigo-600 text-white rounded-xl text-sm font-semibold hover:bg-indigo-700">ค้นหา</button>
    </form>
</div>

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">ผู้ใช้</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">จังหวัด</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">Role</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">สมัครเมื่อ</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">การดำเนินการ</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($users as $user)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4">
                        <div class="font-medium text-gray-800">{{ $user->name }}</div>
                        <div class="text-xs text-gray-500">{{ $user->email }}</div>
                        @if($user->phone) <div class="text-xs text-gray-400">{{ $user->phone }}</div> @endif
                    </td>
                    <td class="px-5 py-4 text-sm text-gray-600">{{ $user->province ?? '-' }}</td>
                    <td class="px-5 py-4">
                        @foreach($user->roles as $role)
                        <span class="px-2 py-1 bg-indigo-100 text-indigo-700 text-xs rounded-full font-medium">{{ $role->name }}</span>
                        @endforeach
                    </td>
                    <td class="px-5 py-4 text-xs text-gray-500">{{ $user->created_at->format('d/m/Y') }}</td>
                    <td class="px-5 py-4">
                        <form action="{{ request()->routeIs('admin.*') ? route('admin.users.role', $user) : route('super-admin.users.role', $user) }}" method="POST" class="flex gap-2">
                            @csrf @method('PATCH')
                            <select name="role" class="px-2 py-1.5 border border-gray-300 rounded-lg text-xs">
                                @foreach($roles as $role)
                                <option value="{{ $role->name }}" {{ $user->hasRole($role->name) ? 'selected' : '' }}>{{ $role->name }}</option>
                                @endforeach
                            </select>
                            @php
                                $provincesList = ['กรุงเทพมหานคร','กระบี่','กาญจนบุรี','กาฬสินธุ์','กำแพงเพชร','ขอนแก่น','จันทบุรี','ฉะเชิงเทรา','ชลบุรี','ชัยนาท','ชัยภูมิ','ชุมพร','เชียงราย','เชียงใหม่','ตรัง','ตราด','ตาก','นครนายก','นครปฐม','นครพนม','นครราชสีมา','นครศรีธรรมราช','นครสวรรค์','นนทบุรี','นราธิวาส','น่าน','บึงกาฬ','บุรีรัมย์','ปทุมธานี','ประจวบคีรีขันธ์','ปราจีนบุรี','ปัตตานี','พระนครศรีอยุธยา','พะเยา','พังงา','พัทลุง','พิจิตร','พิษณุโลก','เพชรบุรี','เพชรบูรณ์','แพร่','ภูเก็ต','มหาสารคาม','มุกดาหาร','แม่ฮ่องสอน','ยโสธร','ยะลา','ร้อยเอ็ด','ระนอง','ระยอง','ราชบุรี','ลพบุรี','ลำปาง','ลำพูน','เลย','ศรีสะเกษ','สกลนคร','สงขลา','สตูล','สมุทรปราการ','สมุทรสงคราม','สมุทรสาคร','สระแก้ว','สระบุรี','สิงห์บุรี','สุโขทัย','สุพรรณบุรี','สุราษฎร์ธานี','สุรินทร์','หนองคาย','หนองบัวลำภู','อ่างทอง','อำนาจเจริญ','อุดรธานี','อุตรดิตถ์','อุทัยธานี','อุบลราชธานี'];
                            @endphp
                            <select name="province" class="px-2 py-1.5 border border-gray-300 rounded-lg text-xs w-28">
                                <option value="">ไม่มีจังหวัด</option>
                                @foreach($provincesList as $p)
                                <option value="{{ $p }}" {{ str_replace('จังหวัด', '', $user->province) == $p ? 'selected' : '' }}>{{ $p }}</option>
                                @endforeach
                            </select>
                            <button type="submit" class="px-3 py-1.5 bg-indigo-600 text-white text-xs rounded-lg hover:bg-indigo-700">บันทึก</button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-5 py-12 text-center text-gray-400">ไม่พบผู้ใช้</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $users->withQueryString()->links() }}
    </div>
</div>
@endsection
