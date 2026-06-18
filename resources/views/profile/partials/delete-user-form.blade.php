<section class="space-y-6">
    <header>
        <h2 class="text-xl font-bold text-red-600">
            ลบบัญชี (Delete Account)
        </h2>
        <p class="mt-1 text-sm text-red-500">
            เมื่อลบบัญชีแล้ว ข้อมูลและทรัพยากรทั้งหมดจะถูกลบอย่างถาวร โปรดดาวน์โหลดข้อมูลที่ต้องการเก็บไว้ก่อนลบบัญชี
        </p>
    </header>

    <button
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
        class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all"
    >
        ลบบัญชีผู้ใช้นี้
    </button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-8">
            @csrf
            @method('delete')

            <h2 class="text-2xl font-bold text-gray-900 mb-2">
                คุณแน่ใจหรือไม่ว่าต้องการลบบัญชี?
            </h2>

            <p class="text-sm text-gray-500 mb-6">
                เมื่อลบบัญชีแล้ว ข้อมูลทั้งหมดจะหายไปอย่างถาวร กรุณาพิมพ์รหัสผ่านของคุณเพื่อยืนยันการลบ
            </p>

            <div class="relative input-floating">
                <input id="password" name="password" type="password" class="block w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all peer placeholder-transparent shadow-sm" placeholder="รหัสผ่าน" />
                <label for="password" class="absolute left-5 top-4 text-gray-500 text-sm transition-all peer-focus:-translate-y-6 peer-focus:text-xs peer-focus:text-red-600 font-medium pointer-events-none origin-left bg-white px-1">รหัสผ่านเพื่อยืนยัน</label>
                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-xs text-red-600 font-medium ml-1" />
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <button type="button" x-on:click="$dispatch('close')" class="px-6 py-3 border border-gray-300 rounded-xl shadow-sm text-sm font-bold text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all">
                    ยกเลิก
                </button>

                <button type="submit" class="px-6 py-3 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all">
                    ยืนยันการลบบัญชี
                </button>
            </div>
        </form>
    </x-modal>
</section>
