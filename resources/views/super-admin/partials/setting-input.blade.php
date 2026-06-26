<div class="flex flex-col md:flex-row md:items-center justify-between gap-4 py-2">
    <div class="flex-1">
        <label for="setting_{{ $setting->key }}" class="font-semibold text-slate-800">{{ $setting->label ?? $setting->key }}</label>
        <div class="text-sm text-slate-500 font-mono mt-0.5">Key: {{ $setting->key }}</div>
    </div>
    <div class="w-full md:w-1/2 flex justify-end">
        @if($setting->type === 'boolean')
            <label class="relative inline-flex items-center cursor-pointer">
                <input type="checkbox" name="{{ $setting->key }}" value="true" class="sr-only peer" {{ $setting->value === 'true' ? 'checked' : '' }}>
                <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600"></div>
            </label>
        @elseif($setting->type === 'string')
            <input type="text" id="setting_{{ $setting->key }}" name="{{ $setting->key }}" value="{{ $setting->value }}" class="w-full px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 text-sm">
        @elseif($setting->type === 'integer')
            <input type="number" id="setting_{{ $setting->key }}" name="{{ $setting->key }}" value="{{ $setting->value }}" class="w-full md:w-32 px-4 py-2 border border-gray-300 rounded-xl focus:ring-2 focus:ring-indigo-500 text-sm text-right">
        @endif
    </div>
</div>
