<?php
$file = 'resources/views/layouts/app.blade.php';
$content = file_get_contents($file);
$replacement = <<<'EOF'
{{-- Active Alert Banner --}}
@php $highestAlert = \App\Models\Alert::where('is_active', true)->orderBy('level', 'desc')->first(); @endphp
@if($highestAlert)
    @php
        $bannerClass = 'bg-yellow-500 text-yellow-900'; $alertText = 'เฝ้าระวัง'; $alertIcon = 'x-heroicon-o-information-circle';
        if ($highestAlert->level == 3) { $bannerClass = 'bg-red-600 text-white'; $alertText = 'อพยพทันที'; $alertIcon = 'x-heroicon-o-exclamation-triangle'; }
        elseif ($highestAlert->level == 2) { $bannerClass = 'bg-orange-500 text-white'; $alertText = 'เตรียมพร้อม'; $alertIcon = 'x-heroicon-o-exclamation-circle'; }
        $alertCount = \App\Models\Alert::where('is_active', true)->where('level', $highestAlert->level)->count();
    @endphp
    <div class="{{ $bannerClass }} text-center py-1.5 px-3 text-[11px] font-medium z-50 relative flex items-center justify-center gap-1 shadow-sm">
        <span class="animate-pulse flex items-center">@svg($alertIcon, 'w-3.5 h-3.5')</span>
        <span>แจ้งเตือนภัยระดับ "{{ $alertText }}" ({{ $alertCount }} จังหวัด)</span>
        <a href="{{ route('alerts.index') }}" class="underline ml-1 font-bold">ดูรายละเอียด</a>
    </div>
@endif
EOF;
$content = preg_replace('/\{\{-- Critical alert banner --\}\}.*?@endif/s', $replacement, $content);
file_put_contents($file, $content);
