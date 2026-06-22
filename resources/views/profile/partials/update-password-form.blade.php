<section x-data="{ open: false }">
    <header @click="open = !open" class="cursor-pointer flex justify-between items-center group">
        <div>
            <h2 class="text-xl font-bold text-gray-900 group-hover:text-red-600 transition-colors">
                เปลี่ยนรหัสผ่าน (Update Password)
            </h2>
            <p class="mt-1 text-sm text-gray-500">
                ตั้งรหัสผ่านใหม่เพื่อความปลอดภัยของบัญชีคุณ
            </p>
        </div>
        <div class="w-10 h-10 rounded-full bg-gray-50 flex items-center justify-center group-hover:bg-red-50 transition-colors">
            <svg class="w-5 h-5 text-gray-500 group-hover:text-red-600 transform transition-transform duration-300" :class="{'rotate-180': open}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
        </div>
    </header>

    <div x-show="open" x-collapse x-cloak style="display: none;">
        <form method="post" action="{{ route('password.update') }}" class="mt-6 space-y-6 pt-4 border-t border-gray-100">
            @csrf
            @method('put')

            <div class="relative input-floating">
                <input id="update_password_current_password" name="current_password" type="password" class="block w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all peer placeholder-transparent shadow-sm" required autocomplete="current-password" placeholder="รหัสผ่านปัจจุบัน" />
                <label for="update_password_current_password" class="absolute left-5 top-4 text-gray-500 text-sm transition-all peer-focus:-translate-y-6 peer-focus:text-xs peer-focus:text-red-600 font-medium pointer-events-none origin-left bg-white px-1">รหัสผ่านปัจจุบัน</label>
                <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-xs text-red-600 font-medium ml-1" />
            </div>

            <div class="relative input-floating">
                <input id="update_password_password" name="password" type="password" class="block w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all peer placeholder-transparent shadow-sm" required autocomplete="new-password" placeholder="รหัสผ่านใหม่" />
                <label for="update_password_password" class="absolute left-5 top-4 text-gray-500 text-sm transition-all peer-focus:-translate-y-6 peer-focus:text-xs peer-focus:text-red-600 font-medium pointer-events-none origin-left bg-white px-1">รหัสผ่านใหม่</label>
                <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-xs text-red-600 font-medium ml-1" />
            </div>

            <div class="relative input-floating">
                <input id="update_password_password_confirmation" name="password_confirmation" type="password" class="block w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all peer placeholder-transparent shadow-sm" required autocomplete="new-password" placeholder="ยืนยันรหัสผ่านใหม่" />
                <label for="update_password_password_confirmation" class="absolute left-5 top-4 text-gray-500 text-sm transition-all peer-focus:-translate-y-6 peer-focus:text-xs peer-focus:text-red-600 font-medium pointer-events-none origin-left bg-white px-1">ยืนยันรหัสผ่านใหม่</label>
                <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-xs text-red-600 font-medium ml-1" />
            </div>

            <div class="flex items-center gap-4 pt-2">
                <button type="submit" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-gray-800 hover:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-900 transition-all">
                    เปลี่ยนรหัสผ่าน
                </button>

                @if (session('status') === 'password-updated')
                    <p
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition
                        x-init="setTimeout(() => show = false, 3000)"
                        class="text-sm font-medium text-green-600 flex items-center gap-1"
                    ><span class="text-lg"><x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0" /></span> อัปเดตรหัสผ่านแล้ว</p>
                @endif
            </div>
        </form>
    </div>
</section>
