<section>
    <header>
        <h2 class="text-xl font-bold text-gray-900">
            ข้อมูลส่วนตัว (Profile Information)
        </h2>
        <p class="mt-1 text-sm text-gray-500">
            อัปเดตข้อมูลบัญชีและที่อยู่อีเมลของคุณ
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="relative input-floating">
            <input id="name" name="name" type="text" class="block w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all peer placeholder-transparent shadow-sm" value="{{ old('name', $user->name) }}" required autofocus autocomplete="name" placeholder="ชื่อ-นามสกุล" />
            <label for="name" class="absolute left-5 top-4 text-gray-500 text-sm transition-all peer-focus:-translate-y-6 peer-focus:text-xs peer-focus:text-red-600 font-medium pointer-events-none origin-left bg-white px-1">ชื่อ-นามสกุล</label>
            <x-input-error class="mt-2 text-xs text-red-600 font-medium ml-1" :messages="$errors->get('name')" />
        </div>



        <div class="relative input-floating">
            <input id="email" name="email" type="email" class="block w-full px-5 py-4 bg-gray-50 border border-gray-200 rounded-2xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent transition-all peer placeholder-transparent shadow-sm" value="{{ old('email', $user->email) }}" required autocomplete="username" placeholder="อีเมล" />
            <label for="email" class="absolute left-5 top-4 text-gray-500 text-sm transition-all peer-focus:-translate-y-6 peer-focus:text-xs peer-focus:text-red-600 font-medium pointer-events-none origin-left bg-white px-1">อีเมล (Email)</label>
            <x-input-error class="mt-2 text-xs text-red-600 font-medium ml-1" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())
                <div class="mt-3">
                    <p class="text-sm text-gray-800">
                        อีเมลของคุณยังไม่ได้รับการยืนยัน
                        <button form="send-verification" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            คลิกที่นี่เพื่อส่งอีเมลยืนยันอีกครั้ง
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-medium text-sm text-green-600">
                            ลิงก์ยืนยันใหม่ได้ถูกส่งไปยังอีเมลของคุณแล้ว
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-2">
            <button type="submit" class="inline-flex items-center justify-center px-6 py-3 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-all">
                บันทึกข้อมูล
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-sm font-medium text-green-600 flex items-center gap-1"
                ><span class="text-lg"><x-heroicon-o-check-circle class="w-5 h-5 inline-block shrink-0" /></span> บันทึกเรียบร้อยแล้ว</p>
            @endif
        </div>
    </form>
    
    <style>
        .input-floating label {
            transition: all 0.2s ease-out;
            padding: 0 0.25rem;
            margin-left: -0.25rem;
            border-radius: 0.25rem;
        }
        .input-floating:focus-within label,
        .input-floating input:not(:placeholder-shown) + label {
            transform: translateY(-1.55rem) scale(0.85);
            color: #dc2626; /* red-600 */
            background-color: white;
        }
    </style>
</section>
