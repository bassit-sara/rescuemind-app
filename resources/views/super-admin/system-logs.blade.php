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
                    <td class="px-5 py-4 text-xs text-gray-600" x-data="{ openDetails: false }">
                        @php
                            $modelNames = [
                                'User'                   => 'ผู้ใช้งาน',
                                'Setting'                => 'การตั้งค่าระบบ',
                                'Alert'                  => 'การแจ้งเตือน',
                                'News'                   => 'ข่าวสาร',
                                'ReliefPoint'            => 'จุดช่วยเหลือ',
                                'SosRequest'             => 'คำร้อง SOS',
                                'Volunteer'              => 'อาสาสมัคร',
                                'MentalAssessment'       => 'แบบประเมินสุขภาพ',
                                'MissingPerson'          => 'รายงานบุคคลสูญหาย',
                                'HazardReport'           => 'รายงานพื้นที่อันตราย',
                                'Resource'               => 'ทรัพยากร',
                                'CommunityNeed'          => 'ความต้องการชุมชน',
                                'HomeRecovery'           => 'การฟื้นฟูที่พักอาศัย',
                                'ShelterBooking'         => 'การจองที่พักพิง',
                                'CustomAssessment'       => 'แบบประเมินกำหนดเอง',
                                'Notification'           => 'การแจ้งเตือนในระบบ',
                                'DatabaseNotification'   => 'การแจ้งเตือนในระบบ',
                                'Mt3Livelihood'          => 'การฟื้นฟูอาชีพ (MT3)',
                                'Mt3HomeRecovery'        => 'การฟื้นฟูบ้าน (MT3)',
                                'Mt3MentalRecovery'      => 'การฟื้นฟูจิตใจ (MT3)',
                                'Livelihood'             => 'การฟื้นฟูอาชีพ',
                                'Donation'               => 'การบริจาค',
                                'ReliefRequest'          => 'คำขอรับความช่วยเหลือ',
                                'Booking'                => 'การจอง',
                            ];
                            $modelBasename = class_basename($log->model_type);
                            $modelNameTh = $modelNames[$modelBasename] ?? $modelBasename;

                            // Shorten UUID-format IDs
                            $displayId = $log->model_id;
                            if($displayId && strlen((string)$displayId) > 20) {
                                $displayId = substr($displayId, 0, 8) . '...';
                            }
                        @endphp
                        
                        @if($log->model_type)
                            <div class="font-bold text-indigo-700 mb-1.5">{{ $modelNameTh }} <span class="text-xs text-gray-400 font-normal">#{{ $displayId }}</span></div>
                            
                            @if(in_array($log->action, ['created', 'updated', 'deleted']))
                                <button @click="openDetails = true" class="inline-flex items-center gap-1 px-2.5 py-1.5 bg-indigo-50 text-indigo-600 rounded-lg font-medium hover:bg-indigo-100 transition-colors">
                                    <x-heroicon-o-eye class="w-4 h-4" /> ดูรายละเอียด
                                </button>
                                
                                <!-- Modal -->
                                <div x-show="openDetails" style="display: none;" class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6">
                                    <!-- Backdrop -->
                                    <div x-show="openDetails" x-transition.opacity class="fixed inset-0 bg-slate-900/50 backdrop-blur-sm" @click="openDetails = false"></div>
                                    
                                    <!-- Modal Panel -->
                                    <div x-show="openDetails" 
                                         x-transition:enter="transition ease-out duration-300"
                                         x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                         x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                                         x-transition:leave="transition ease-in duration-200"
                                         x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                                         x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                                         class="relative w-full max-w-2xl bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
                                         
                                         <!-- Header -->
                                         <div class="px-6 py-4 border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                                            <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                                                <x-heroicon-o-document-magnifying-glass class="w-5 h-5 text-indigo-600" />
                                                รายละเอียดการเปลี่ยนแปลง
                                            </h3>
                                            <button @click="openDetails = false" class="text-slate-400 hover:text-slate-600 p-1 rounded-lg hover:bg-slate-100 transition-colors">
                                                <x-heroicon-o-x-mark class="w-5 h-5" />
                                            </button>
                                         </div>
                                         
                                         <!-- Body -->
                                         <div class="p-6 overflow-y-auto">
                                            <div class="mb-6 flex gap-4 text-sm">
                                                <div><span class="text-slate-500">ประเภทข้อมูล:</span> <span class="font-bold text-slate-800">{{ $modelNameTh }}</span></div>
                                                <div><span class="text-slate-500">รหัสอ้างอิง:</span> <span class="font-bold text-slate-800">#{{ $log->model_id }}</span></div>
                                            </div>

                                            @if($log->action === 'updated' && $log->new_values)
                                                @php $sensitiveFields = ['remember_token','password','api_token','token','secret','two_factor_secret','two_factor_recovery_codes']; @endphp
                                                <div class="space-y-3">
                                                    @foreach($log->new_values as $key => $newValue)
                                                        @php
                                                            if(in_array($key, ['updated_at', 'created_at'])) continue;
                                                            if(in_array($key, $sensitiveFields)) continue;
                                                            $oldValue = $log->old_values[$key] ?? null;

                                                            // Decode JSON strings to readable arrays
                                                            $decodedNew = null;
                                                            $decodedOld = null;
                                                            if(is_string($newValue)) {
                                                                $try = json_decode($newValue, true);
                                                                if(json_last_error() === JSON_ERROR_NONE && is_array($try)) $decodedNew = $try;
                                                            }
                                                            if(is_string($oldValue)) {
                                                                $try = json_decode($oldValue, true);
                                                                if(json_last_error() === JSON_ERROR_NONE && is_array($try)) $decodedOld = $try;
                                                            }
                                                        @endphp
                                                        <div class="bg-slate-50 border border-slate-200 rounded-xl p-4">
                                                            <div class="font-semibold text-slate-700 mb-3 flex items-center gap-2">
                                                                <x-heroicon-s-tag class="w-4 h-4 text-indigo-400" />
                                                                ฟิลด์: <span class="text-indigo-600 font-mono bg-indigo-50 px-2 py-0.5 rounded">{{ $key }}</span>
                                                            </div>
                                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                                <div class="bg-red-50/50 border border-red-100 p-3 rounded-lg">
                                                                    <div class="text-[10px] text-red-500 uppercase font-bold tracking-wider mb-1 flex items-center gap-1"><x-heroicon-o-minus-circle class="w-3 h-3" /> ค่าเดิม</div>
                                                                    @if($decodedOld)
                                                                        @foreach($decodedOld as $k => $v)
                                                                            <div class="text-red-700 text-xs mb-1"><span class="font-bold">{{ $k }}:</span> {{ is_array($v) ? json_encode($v, JSON_UNESCAPED_UNICODE) : $v }}</div>
                                                                        @endforeach
                                                                    @else
                                                                        <div class="text-red-700 font-mono text-sm break-words">{{ is_array($oldValue) ? json_encode($oldValue, JSON_UNESCAPED_UNICODE) : ($oldValue ?: '-') }}</div>
                                                                    @endif
                                                                </div>
                                                                <div class="bg-emerald-50/50 border border-emerald-100 p-3 rounded-lg">
                                                                    <div class="text-[10px] text-emerald-500 uppercase font-bold tracking-wider mb-1 flex items-center gap-1"><x-heroicon-o-plus-circle class="w-3 h-3" /> ค่าใหม่</div>
                                                                    @if($decodedNew)
                                                                        @foreach($decodedNew as $k => $v)
                                                                            <div class="text-emerald-700 text-xs mb-1"><span class="font-bold">{{ $k }}:</span> {{ is_array($v) ? json_encode($v, JSON_UNESCAPED_UNICODE) : $v }}</div>
                                                                        @endforeach
                                                                    @else
                                                                        <div class="text-emerald-700 font-mono text-sm break-words">{{ is_array($newValue) ? json_encode($newValue, JSON_UNESCAPED_UNICODE) : ($newValue ?: '-') }}</div>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @elseif($log->action === 'deleted' && $log->old_values)
                                                @php $sensitiveFields = ['remember_token','password','api_token','token','secret','two_factor_secret','two_factor_recovery_codes']; @endphp
                                                <div class="bg-red-50 text-red-700 p-5 rounded-xl border border-red-100">
                                                    <div class="font-bold mb-3 flex items-center gap-2"><x-heroicon-o-trash class="w-5 h-5" /> ข้อมูลที่ถูกลบ:</div>
                                                    <div class="bg-white/60 rounded-lg p-3 text-sm break-words overflow-x-auto space-y-1.5">
                                                        @foreach($log->old_values as $key => $val)
                                                            @if(!in_array($key, ['updated_at', 'created_at']) && !in_array($key, $sensitiveFields))
                                                                @php
                                                                    $decoded = null;
                                                                    if(is_string($val)) {
                                                                        $try = json_decode($val, true);
                                                                        if(json_last_error() === JSON_ERROR_NONE && is_array($try)) $decoded = $try;
                                                                    }
                                                                @endphp
                                                                <div>
                                                                    <span class="text-red-400 font-bold font-mono">{{ $key }}:</span>
                                                                    @if($decoded)
                                                                        <div class="ml-3 mt-1 space-y-0.5">
                                                                            @foreach($decoded as $k => $v)
                                                                                <div class="text-red-600 text-xs"><span class="font-semibold">{{ $k }}:</span> {{ is_array($v) ? json_encode($v, JSON_UNESCAPED_UNICODE) : $v }}</div>
                                                                            @endforeach
                                                                        </div>
                                                                    @else
                                                                        <span class="font-mono">{{ is_array($val) ? json_encode($val, JSON_UNESCAPED_UNICODE) : $val }}</span>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @elseif($log->action === 'created' && $log->new_values)
                                                @php $sensitiveFields = ['remember_token','password','api_token','token','secret','two_factor_secret','two_factor_recovery_codes']; @endphp
                                                <div class="bg-blue-50 text-blue-700 p-5 rounded-xl border border-blue-100">
                                                    <div class="font-bold mb-3 flex items-center gap-2"><x-heroicon-o-document-plus class="w-5 h-5" /> ข้อมูลที่ถูกสร้างใหม่:</div>
                                                    <div class="bg-white/60 rounded-lg p-3 text-sm break-words overflow-x-auto space-y-1.5">
                                                        @foreach($log->new_values as $key => $val)
                                                            @if(!in_array($key, ['updated_at', 'created_at']) && !in_array($key, $sensitiveFields))
                                                                @php
                                                                    $decoded = null;
                                                                    if(is_string($val)) {
                                                                        $try = json_decode($val, true);
                                                                        if(json_last_error() === JSON_ERROR_NONE && is_array($try)) $decoded = $try;
                                                                    }
                                                                @endphp
                                                                <div>
                                                                    <span class="text-blue-400 font-bold font-mono">{{ $key }}:</span>
                                                                    @if($decoded)
                                                                        <div class="ml-3 mt-1 space-y-0.5">
                                                                            @foreach($decoded as $k => $v)
                                                                                <div class="text-blue-600 text-xs"><span class="font-semibold">{{ $k }}:</span> {{ is_array($v) ? json_encode($v, JSON_UNESCAPED_UNICODE) : $v }}</div>
                                                                            @endforeach
                                                                        </div>
                                                                    @else
                                                                        <span class="font-mono">{{ is_array($val) ? json_encode($val, JSON_UNESCAPED_UNICODE) : $val }}</span>
                                                                    @endif
                                                                </div>
                                                            @endif
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                         </div>
                                         
                                         <!-- Footer -->
                                         <div class="px-6 py-4 border-t border-slate-100 bg-slate-50 flex justify-end">
                                            <button @click="openDetails = false" class="px-4 py-2 bg-slate-800 text-white text-sm font-medium rounded-lg hover:bg-slate-700 transition-colors">
                                                ปิดหน้าต่าง
                                            </button>
                                         </div>
                                    </div>
                                </div>
                            @endif
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
