@php
    $dinas_logo = \Illuminate\Support\Facades\Cache::get('dinas_app_logo');
    $logo = $dinas_logo ?? asset('adminlte/dist/img/AdminLTELogo.png');
    $appName = \App\Models\AppSetting::getSetting('app_name', config('app.name'));

    // Legal Links Logic
    $privacyLink = \App\Models\AppSetting::getSetting('link_privacy');
    $privacyLink = ($privacyLink && $privacyLink !== '#') ? $privacyLink : route('page.show', 'privacy-policy');

    $termsLink = \App\Models\AppSetting::getSetting('link_terms');
    $termsLink = ($termsLink && $termsLink !== '#') ? $termsLink : route('page.show', 'terms-of-service');
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Registrasi Sekolah - {{ $appName }}</title>
    <link rel="icon" type="image/x-icon"
        href="{{ \App\Models\AppSetting::getSetting('app_favicon') ? asset(\App\Models\AppSetting::getSetting('app_favicon')) : asset('favicon.ico') }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&amp;display=swap"
        rel="stylesheet" />
    <link
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap"
        rel="stylesheet" />
    <script>
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    colors: {
                        "primary": "#135bec",
                        "background-light": "#f6f6f8",
                        "background-dark": "#101622",
                    },
                    fontFamily: {
                        "display": ["Public Sans"]
                    },
                    borderRadius: {
                        "DEFAULT": "0.25rem",
                        "lg": "0.5rem",
                        "xl": "0.75rem",
                        "full": "9999px"
                    },
                },
            },
        }
    </script>
    <style>
        body {
            font-family: "Public Sans", sans-serif;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark min-h-screen flex flex-col">

    <!-- Main Content Area -->
    <main class="flex-1 w-full flex">
        <!-- Left Side (Image & branding) - Hidden on Mobile -->
        <div class="hidden lg:flex w-1/2 bg-slate-100 flex-col justify-between p-12 relative overflow-hidden">
            <!-- Dynamic Background Image -->
            <div class="absolute inset-0 z-0">
                <img src="{{ isset(\App\Models\AppSetting::all()->pluck('value', 'key')['login_cover_image']) ? asset(\App\Models\AppSetting::all()->pluck('value', 'key')['login_cover_image']) : 'https://images.unsplash.com/photo-1541339907198-e08756dedf3f?q=80&w=2070&auto=format&fit=crop' }}"
                    class="w-full h-full object-cover" alt="Login Cover">
                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/40 to-black/10"></div>
            </div>

            <div class="relative z-10">
                <div
                    class="size-24 bg-white/20 backdrop-blur-md rounded-xl flex items-center justify-center mb-6 border border-white/20 p-4">
                    <img src="{{ \App\Models\AppSetting::getSetting('app_logo') ? asset(\App\Models\AppSetting::getSetting('app_logo')) : asset('adminlte/dist/img/AdminLTELogo.png') }}"
                        class="w-full h-full object-contain" alt="App Logo">
                </div>
                <h2 class="text-4xl font-bold text-white mb-4 leading-tight">
                    {{ \App\Models\AppSetting::getSetting('app_name', 'EduArchive') }}
                </h2>
                <p class="text-white/80 text-lg max-w-md">
                    {{ \App\Models\AppSetting::getSetting('app_description', 'Solusi terintegrasi untuk manajemen surat, arsip digital, dan legalisir ijazah yang aman dan efisien.') }}
                </p>
            </div>

            <div class="relative z-10 flex gap-4 text-white/60 text-sm font-medium">
                <span>&copy; {{ date('Y') }} {{ $appName }}</span>
                <span>&bull;</span>
                <a href="{{ $privacyLink }}" class="hover:text-white transition-colors">Privacy Policy</a>
                <span>&bull;</span>
                <a href="{{ $termsLink }}" class="hover:text-white transition-colors">Terms of Service</a>
            </div>
        </div>

        <!-- Right Side (Register Form) -->
        <div
            class="w-full lg:w-1/2 flex items-center justify-center p-6 md:p-12 bg-white dark:bg-slate-900 overflow-y-auto">
            <div class="w-full max-w-[600px]">

                <!-- Mobile Logo (Visible only on small screens) -->
                <div class="lg:hidden mb-8 flex items-center gap-2">
                    <img src="{{ $logo }}" alt="Logo" class="h-10 w-auto">
                    <span class="text-xl font-bold text-slate-900 dark:text-white">{{ $appName }}</span>
                </div>

                <!-- Welcome Section -->
                <div class="mb-8">
                    <h1
                        class="text-[#0d121b] dark:text-white text-3xl font-extrabold leading-tight tracking-tight mb-2">
                        Daftarkan Sekolah Baru 🏫
                    </h1>
                    <p class="text-slate-500 dark:text-slate-400 text-base">Bergabunglah dengan jaringan {{ $appName }}
                        hari ini.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Identitas Sekolah -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-2 mb-4">
                        <h3 class="text-lg font-bold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">school</span> Identitas Sekolah
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- NPSN -->
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-[#0d121b] dark:text-slate-200">NPSN</label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-3.5 text-slate-400 text-[20px]">badge</span>
                                <input name="npsn" value="{{ old('npsn') }}"
                                    class="w-full rounded-xl border {{ $errors->has('npsn') ? 'border-red-500' : 'border-slate-200' }} dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-[#0d121b] dark:text-white h-12 pl-12 pr-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-slate-400 font-medium"
                                    maxlength="8" placeholder="Contoh: 10101010" type="text" required />
                            </div>
                            @error('npsn')
                                <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jenjang -->
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-[#0d121b] dark:text-slate-200">Jenjang</label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-3.5 text-slate-400 text-[20px]">school</span>
                                <select name="jenjang"
                                    class="w-full rounded-xl border {{ $errors->has('jenjang') ? 'border-red-500' : 'border-slate-200' }} dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-[#0d121b] dark:text-white h-12 pl-12 pr-10 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all appearance-none font-medium">
                                    <option value="" disabled selected>-- Pilih Jenjang --</option>
                                    @foreach(\App\Models\SchoolLevel::orderBy('sequence')->get() as $level)
                                        <option value="{{ $level->name }}" {{ old('jenjang') == $level->name ? 'selected' : '' }}>
                                            {{ $level->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span
                                    class="material-symbols-outlined absolute right-4 top-3.5 text-slate-400 text-[20px] pointer-events-none">arrow_drop_down</span>
                            </div>
                            @error('jenjang')
                                <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kode Sekolah (ID) -->
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-[#0d121b] dark:text-slate-200">Kode Sekolah (URL
                            Aplikasi)</label>
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-4 top-3.5 text-slate-400 text-[20px]">link</span>
                            <input name="id" id="id_input" value="{{ old('id') }}"
                                class="w-full rounded-xl border {{ $errors->has('id') ? 'border-red-500' : 'border-slate-200' }} dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-[#0d121b] dark:text-white h-12 pl-12 pr-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-slate-400 font-medium"
                                placeholder="Contoh: smpn1" type="text" required />
                        </div>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 font-medium">
                            URL Aplikasi: <span class="font-bold text-primary">{{ request()->getHost() }}/<span
                                    id="url_preview">...</span></span>
                        </p>
                        @error('id')
                            <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Sekolah -->
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-[#0d121b] dark:text-slate-200">Nama Sekolah</label>
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-4 top-3.5 text-slate-400 text-[20px]">domain</span>
                            <input name="nama_sekolah" value="{{ old('nama_sekolah') }}"
                                class="w-full rounded-xl border {{ $errors->has('nama_sekolah') ? 'border-red-500' : 'border-slate-200' }} dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-[#0d121b] dark:text-white h-12 pl-12 pr-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-slate-400 font-medium"
                                placeholder="Contoh: SMA Negeri 1 Jakarta" type="text" required />
                        </div>
                        @error('nama_sekolah')
                            <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="space-y-1.5">
                        <label class="text-sm font-bold text-[#0d121b] dark:text-slate-200">Alamat Lengkap</label>
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-4 top-3.5 text-slate-400 text-[20px]">location_on</span>
                            <textarea name="alamat" rows="2"
                                class="w-full rounded-xl border {{ $errors->has('alamat') ? 'border-red-500' : 'border-slate-200' }} dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-[#0d121b] dark:text-white pl-12 pr-4 py-3 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-slate-400 font-medium"
                                placeholder="Jl. Raya..." required>{{ old('alamat') }}</textarea>
                        </div>
                        @error('alamat')
                            <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Akun Admin -->
                    <div class="border-b border-gray-200 dark:border-gray-700 pb-2 mb-4 pt-4">
                        <h3 class="text-lg font-bold text-slate-700 dark:text-slate-300 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">manage_accounts</span> Akun
                            Administrator
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Nama Admin -->
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-[#0d121b] dark:text-slate-200">Nama Admin</label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-3.5 text-slate-400 text-[20px]">person</span>
                                <input name="name" value="{{ old('name') }}"
                                    class="w-full rounded-xl border {{ $errors->has('name') ? 'border-red-500' : 'border-slate-200' }} dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-[#0d121b] dark:text-white h-12 pl-12 pr-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-slate-400 font-medium"
                                    placeholder="Nama Lengkap" type="text" required />
                            </div>
                            @error('name')
                                <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-[#0d121b] dark:text-slate-200">Email Admin</label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-3.5 text-slate-400 text-[20px]">mail</span>
                                <input name="email" value="{{ old('email') }}"
                                    class="w-full rounded-xl border {{ $errors->has('email') ? 'border-red-500' : 'border-slate-200' }} dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-[#0d121b] dark:text-white h-12 pl-12 pr-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-slate-400 font-medium"
                                    placeholder="admin@sekolah.sch.id" type="email" required />
                            </div>
                            @error('email')
                                <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Password -->
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-[#0d121b] dark:text-slate-200">Password</label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-3.5 text-slate-400 text-[20px]">lock</span>
                                <input name="password" id="password"
                                    class="w-full rounded-xl border {{ $errors->has('password') ? 'border-red-500' : 'border-slate-200' }} dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-[#0d121b] dark:text-white h-12 pl-12 pr-12 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-slate-400 font-medium"
                                    placeholder="Min. 8 karakter" type="password" required />
                                <button
                                    class="absolute right-4 top-3.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors"
                                    type="button" onclick="togglePassword('password', 'eyeIcon1')">
                                    <span class="material-symbols-outlined text-[20px]" id="eyeIcon1">visibility</span>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-1.5">
                            <label class="text-sm font-bold text-[#0d121b] dark:text-slate-200">Konfirmasi</label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-3.5 text-slate-400 text-[20px]">lock_reset</span>
                                <input name="password_confirmation" id="password_confirmation"
                                    class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-[#0d121b] dark:text-white h-12 pl-12 pr-12 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-slate-400 font-medium"
                                    placeholder="Ulangi Password" type="password" required />
                                <button
                                    class="absolute right-4 top-3.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors"
                                    type="button" onclick="togglePassword('password_confirmation', 'eyeIcon2')">
                                    <span class="material-symbols-outlined text-[20px]" id="eyeIcon2">visibility</span>
                                </button>
                            </div>
                        </div>
                    </div>


                    <div class="flex items-center gap-3 py-2">
                        <input
                            class="size-5 rounded border-slate-300 dark:border-slate-700 text-primary focus:ring-primary/20 cursor-pointer"
                            id="terms" type="checkbox" required />
                        <label class="text-sm text-slate-600 dark:text-slate-400 font-medium" for="terms">
                            Saya menyetujui <a class="font-bold text-primary hover:underline"
                                href="{{ $termsLink }}">Syarat &
                                Ketentuan</a> dan <a class="font-bold text-primary hover:underline"
                                href="{{ $privacyLink }}">Kebijakan
                                Privasi</a>.
                        </label>
                    </div>

                    <button
                        class="w-full bg-primary hover:bg-blue-700 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-primary/30 hover:shadow-primary/50 flex items-center justify-center gap-2 transform active:scale-[0.98]"
                        type="submit">
                        <span>Daftarkan Sekolah</span>
                        <span class="material-symbols-outlined text-xl">app_registration</span>
                    </button>
                </form>

                <div class="mt-8 text-center">
                    <p class="text-slate-500 dark:text-slate-400 text-sm">
                        Sudah punya akun?
                        <a class="text-primary font-bold hover:underline ml-1" href="{{ route('login') }}">Masuk ke
                            Portal</a>
                    </p>
                </div>

                <div class="flex items-center justify-center gap-2 pt-8">
                    <span class="material-symbols-outlined text-green-500 text-lg">verified</span>
                    <p class="text-xs text-slate-500 dark:text-slate-500 font-medium">Enkripsi AES-256 Bit Aktif</p>
                </div>
            </div>
        </div>
    </main>

    <!-- Script to handle URL Preview & Password Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const idInput = document.getElementById('id_input');
            const urlPreview = document.getElementById('url_preview');

            if (idInput && urlPreview) {
                // Initial check
                if (idInput.value) {
                    urlPreview.innerText = idInput.value;
                } else {
                    urlPreview.innerText = '...';
                }

                idInput.addEventListener('input', function (e) {
                    let val = e.target.value.replace(/[^a-zA-Z0-9-]/g, '').toLowerCase();
                    e.target.value = val; // Enforce lowercase and alphanumeric + dash
                    urlPreview.innerText = val || '...';
                });
            }
        });

        function togglePassword(inputId, iconId) {
            const input = document.getElementById(inputId);
            const icon = document.getElementById(iconId);
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerText = 'visibility_off';
            } else {
                input.type = 'password';
                icon.innerText = 'visibility';
            }
        }
    </script>
</body>

</html>