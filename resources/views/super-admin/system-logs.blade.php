@extends('layouts.admin')
@section('title', 'ประวัติการใช้งาน')
@section('page-title')
    <x-heroicon-o-clock class="w-5 h-5 inline-block shrink-0" /> ประวัติการใช้งาน (System Logs)
@endsection
@section('content')

<div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">เวลา</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">ผู้ใช้</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">Role</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">การกระทำ</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">IP Address</th>
                    <th class="px-5 py-3 text-left text-xs font-bold text-gray-500 uppercase">ข้อมูล (Model / Changes)</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @forelse($logs as $log)
                <tr class="hover:bg-gray-50 transition-colors">
                    <td class="px-5 py-4 text-sm text-gray-500 whitespace-nowrap">
                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                    </td>
                    <td class="px-5 py-4">
                        <div class="font-medium text-gray-800">{{ $log->user->name ?? 'Unknown' }}</div>
                        <div class="text-xs text-gray-500">{{ $log->user->email ?? '' }}</div>
                    </td>
                    <td class="px-5 py-4">
                        <span class="px-2 py-1 bg-indigo-100 text-indigo-700 text-xs rounded-full font-medium">{{ $log->role }}</span>
                    </td>
                    <td class="px-5 py-4">
                        @php
                            $actionColors = [
                                'login' => 'bg-green-100 text-green-700',
                                'logout' => 'bg-gray-100 text-gray-700',
                                'created' => 'bg-blue-100 text-blue-700',
                                'updated' => 'bg-yellow-100 text-yellow-700',
                                'deleted' => 'bg-red-100 text-red-700',
                            ];
                            $color = $actionColors[$log->action] ?? 'bg-gray-100 text-gray-700';
                        @endphp
                        <span class="px-2 py-1 {{ $color }} text-xs rounded-full font-bold uppercase">{{ $log->action }}</span>
                    </td>
                    <td class="px-5 py-4 text-xs text-gray-500">
                        {{ $log->ip_address ?? '-' }}
                    </td>
                    <td class="px-5 py-4 text-xs text-gray-600">
                        @if($log->model_type)
                            <div class="font-bold text-indigo-600 mb-1">{{ class_basename($log->model_type) }} #{{ $log->model_id }}</div>
                        @endif
                        
                        @if($log->action === 'updated' && $log->new_values)
                            <div class="bg-gray-100 p-2 rounded text-[10px] font-mono overflow-x-auto max-w-xs" style="max-height: 100px;">
                                @foreach($log->new_values as $key => $value)
                                    <div><span class="text-gray-400">{{ $key }}:</span> {{ is_array($value) ? json_encode($value, JSON_UNESCAPED_UNICODE) : $value }}</div>
                                @endforeach
                            </div>
                        @elseif($log->action === 'deleted' && $log->old_values)
                            <div class="text-[10px] text-red-500">ถูกลบไปแล้ว</div>
                        @elseif($log->action === 'created' && $log->new_values)
                            <div class="text-[10px] text-blue-500">สร้างใหม่</div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="px-5 py-12 text-center text-gray-400">ยังไม่มีประวัติการใช้งาน</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="px-5 py-4 border-t border-gray-100">
        {{ $logs->links() }}
    </div>
</div>
@endsection
