@php
    $dinas_logo = \Illuminate\Support\Facades\Cache::get('dinas_app_logo');
    // If not in cache, try to get from DB/Storage, otherwise fallback to default
    if (!$dinas_logo) {
        $logoSetting = \App\Models\AppSetting::where('key', 'dinas_app_logo')->first();
        $dinas_logo = $logoSetting ? \Illuminate\Support\Facades\Storage::url($logoSetting->value) : asset('adminlte/dist/img/AdminLTELogo.png');
    }

    $appName = \App\Models\AppSetting::getSetting('app_name', 'EduArchive');
    $appDesc = \App\Models\AppSetting::getSetting('app_description', 'Digitalisasi arsip sekolah untuk masa depan yang lebih baik.');
@endphp

<!DOCTYPE html>
<html class="light" lang="id">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Registrasi Sekolah - {{ $appName }}</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;900&amp;display=swap"
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
                        "display": ["Public Sans", "sans-serif"]
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
            font-family: 'Public Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark text-[#0d121b] antialiased">
    <div class="flex min-h-screen w-full flex-col lg:flex-row">
        <!-- Left Panel: Branding & Value Prop -->
        <div class="relative hidden w-full flex-col justify-between bg-primary p-12 lg:flex lg:w-2/5">
            <div class="relative z-10">
                <div class="flex items-center gap-3 text-white">
                    <div class="size-12 rounded-lg bg-white/10 flex items-center justify-center backdrop-blur-sm p-2">
                        <img src="{{ $dinas_logo }}" alt="Logo"
                            class="w-full h-full object-contain filter brightness-0 invert">
                    </div>
                    <h1 class="text-2xl font-black tracking-tight">{{ $appName }}</h1>
                </div>
            </div>
            <div class="relative z-10 space-y-6">
                <h2 class="text-4xl font-bold leading-tight text-white">{{ $appDesc }}</h2>
                <p class="text-lg text-white/80">Bergabunglah dengan ribuan sekolah lainnya dalam menjaga sejarah
                    pendidikan dengan keamanan tingkat perusahaan.</p>
                <div class="grid grid-cols-1 gap-6 pt-8">
                    <div class="flex items-start gap-4">
                        <span
                            class="material-symbols-outlined text-white bg-white/10 p-2 rounded-lg">verified_user</span>
                        <div>
                            <p class="font-bold text-white">Kepatuhan Standar Nasional</p>
                            <p class="text-sm text-white/70">Sepenuhnya selaras dengan protokol keamanan data pendidikan
                                nasional.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-4">
                        <span class="material-symbols-outlined text-white bg-white/10 p-2 rounded-lg">cloud_done</span>
                        <div>
                            <p class="font-bold text-white">Ketahanan Cloud</p>
                            <p class="text-sm text-white/70">Pencadangan otomatis dan uptime 99.9% untuk catatan
                                administratif.</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Decorative background pattern -->
            <div class="absolute inset-0 opacity-10 pointer-events-none overflow-hidden">
                <div class="absolute -right-20 -top-20 w-96 h-96 rounded-full bg-white/20 blur-3xl"></div>
                <div class="absolute -left-20 -bottom-20 w-96 h-96 rounded-full bg-white/20 blur-3xl"></div>
                <svg class="h-full w-full" preserveAspectRatio="none" viewBox="0 0 100 100">
                    <defs>
                        <pattern height="10" id="grid" patternUnits="userSpaceOnUse" width="10">
                            <path d="M 10 0 L 0 0 0 10" fill="none" stroke="white" stroke-width="0.5"></path>
                        </pattern>
                    </defs>
                    <rect fill="url(#grid)" height="100" width="100"></rect>
                </svg>
            </div>
            <div class="relative z-10 text-sm text-white/50">
                © {{ date('Y') }} {{ $appName }}. All rights reserved.
            </div>
        </div>

        <!-- Right Panel: Registration Form -->
        <div class="flex w-full items-center justify-center p-6 sm:p-12 lg:w-3/5 overflow-y-auto max-h-screen">
            <div class="w-full max-w-[600px] space-y-8 my-auto">
                <!-- Mobile Logo -->
                <div class="flex items-center gap-2 lg:hidden mb-8">
                    <img src="{{ $dinas_logo }}" alt="Logo" class="h-10 w-auto object-contain">
                    <h1 class="text-xl font-bold">{{ $appName }}</h1>
                </div>

                <div class="space-y-2">
                    <h2 class="text-3xl font-black tracking-tight text-[#0d121b] dark:text-white">Daftarkan Sekolah Anda
                    </h2>
                    <p class="text-[#4c669a] dark:text-gray-400">Bergabunglah dengan jaringan {{ $appName }} hari ini
                        untuk
                        mulai mengelola arsip Anda.</p>
                </div>

                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Identitas Sekolah -->
                    <div class="border-b border-gray-200 pb-2 mb-4">
                        <h3 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">school</span> Identitas Sekolah
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- NPSN -->
                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-[#0d121b] dark:text-gray-200">NPSN</label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl">badge</span>
                                <input name="npsn" value="{{ old('npsn') }}"
                                    class="w-full rounded-lg border {{ $errors->has('npsn') ? 'border-red-500' : 'border-[#cfd7e7]' }} bg-white px-11 py-3.5 text-base focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white transition-all outline-none"
                                    maxlength="8" placeholder="Contoh: 10101010" type="text" required />
                            </div>
                            @error('npsn')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Jenjang -->
                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-[#0d121b] dark:text-gray-200">Jenjang</label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl">school</span>
                                <select name="jenjang"
                                    class="w-full rounded-lg border {{ $errors->has('jenjang') ? 'border-red-500' : 'border-[#cfd7e7]' }} bg-white px-11 py-3.5 text-base focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white transition-all outline-none appearance-none">
                                    <option value="" disabled selected>-- Pilih Jenjang --</option>
                                    @foreach(\App\Models\SchoolLevel::orderBy('sequence')->get() as $level)
                                        <option value="{{ $level->name }}" {{ old('jenjang') == $level->name ? 'selected' : '' }}>
                                            {{ $level->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <span
                                    class="material-symbols-outlined absolute right-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl pointer-events-none">arrow_drop_down</span>
                            </div>
                            @error('jenjang')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- Kode Sekolah (ID) -->
                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-[#0d121b] dark:text-gray-200">Kode Sekolah (URL
                            Aplikasi)</label>
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl">link</span>
                            <input name="id" id="id_input" value="{{ old('id') }}"
                                class="w-full rounded-lg border {{ $errors->has('id') ? 'border-red-500' : 'border-[#cfd7e7]' }} bg-white px-11 py-3.5 text-base focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white transition-all outline-none"
                                placeholder="Contoh: smpn1" type="text" required />
                        </div>
                        <p class="text-xs text-[#4c669a] dark:text-gray-400 mt-1">
                            URL Aplikasi akan menjadi: <span
                                class="font-bold text-primary">{{ request()->getHost() }}/<span
                                    id="url_preview">...</span></span>
                        </p>
                        @error('id')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Nama Sekolah -->
                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-[#0d121b] dark:text-gray-200">Nama Sekolah</label>
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl">domain</span>
                            <input name="nama_sekolah" value="{{ old('nama_sekolah') }}"
                                class="w-full rounded-lg border {{ $errors->has('nama_sekolah') ? 'border-red-500' : 'border-[#cfd7e7]' }} bg-white px-11 py-3.5 text-base focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white transition-all outline-none"
                                placeholder="Contoh: SMA Negeri 1 Jakarta" type="text" required />
                        </div>
                        @error('nama_sekolah')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Alamat -->
                    <div class="space-y-1.5">
                        <label class="text-sm font-semibold text-[#0d121b] dark:text-gray-200">Alamat Lengkap</label>
                        <div class="relative">
                            <span
                                class="material-symbols-outlined absolute left-4 top-4 text-gray-400 text-xl">location_on</span>
                            <textarea name="alamat" rows="2"
                                class="w-full rounded-lg border {{ $errors->has('alamat') ? 'border-red-500' : 'border-[#cfd7e7]' }} bg-white px-11 py-3.5 text-base focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white transition-all outline-none"
                                placeholder="Jl. Raya..." required>{{ old('alamat') }}</textarea>
                        </div>
                        @error('alamat')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Akun Admin -->
                    <div class="border-b border-gray-200 pb-2 mb-4 pt-4">
                        <h3 class="text-lg font-semibold text-gray-700 flex items-center gap-2">
                            <span class="material-symbols-outlined text-primary">manage_accounts</span> Akun
                            Administrator
                        </h3>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Nama Admin -->
                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-[#0d121b] dark:text-gray-200">Nama Admin</label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl">person</span>
                                <input name="name" value="{{ old('name') }}"
                                    class="w-full rounded-lg border {{ $errors->has('name') ? 'border-red-500' : 'border-[#cfd7e7]' }} bg-white px-11 py-3.5 text-base focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white transition-all outline-none"
                                    placeholder="Nama Lengkap" type="text" required />
                            </div>
                            @error('name')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Email -->
                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-[#0d121b] dark:text-gray-200">Email Admin</label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl">mail</span>
                                <input name="email" value="{{ old('email') }}"
                                    class="w-full rounded-lg border {{ $errors->has('email') ? 'border-red-500' : 'border-[#cfd7e7]' }} bg-white px-11 py-3.5 text-base focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white transition-all outline-none"
                                    placeholder="admin@sekolah.sch.id" type="email" required />
                            </div>
                            @error('email')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                        <!-- Password -->
                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-[#0d121b] dark:text-gray-200">Password</label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl">lock</span>
                                <input name="password"
                                    class="w-full rounded-lg border {{ $errors->has('password') ? 'border-red-500' : 'border-[#cfd7e7]' }} bg-white px-11 py-3.5 text-base focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white transition-all outline-none"
                                    placeholder="Min. 8 karakter" type="password" required />
                            </div>
                            @error('password')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Confirm Password -->
                        <div class="space-y-1.5">
                            <label class="text-sm font-semibold text-[#0d121b] dark:text-gray-200">Konfirmasi</label>
                            <div class="relative">
                                <span
                                    class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 text-xl">lock_reset</span>
                                <input name="password_confirmation"
                                    class="w-full rounded-lg border border-[#cfd7e7] bg-white px-11 py-3.5 text-base focus:border-primary focus:ring-2 focus:ring-primary/20 dark:border-gray-700 dark:bg-gray-800 dark:text-white transition-all outline-none"
                                    placeholder="Ulangi Password" type="password" required />
                            </div>
                        </div>
                    </div>


                    <div class="flex items-center gap-3 py-2">
                        <input class="h-5 w-5 rounded border-[#cfd7e7] text-primary focus:ring-primary" id="terms"
                            type="checkbox" required />
                        <label class="text-sm text-[#4c669a] dark:text-gray-400" for="terms">
                            Saya menyetujui <a class="font-semibold text-primary hover:underline" href="#">Syarat &
                                Ketentuan</a> dan <a class="font-semibold text-primary hover:underline"
                                href="#">Kebijakan
                                Privasi</a>.
                        </label>
                    </div>

                    <button
                        class="w-full rounded-lg bg-primary py-4 text-center text-base font-bold text-white shadow-lg shadow-primary/20 transition-all hover:bg-primary/90 active:scale-[0.98]">
                        Daftarkan Sekolah
                    </button>
                </form>

                <div class="relative py-4">
                    <div class="absolute inset-0 flex items-center">
                        <div class="w-full border-t border-[#e7ebf3] dark:border-gray-700"></div>
                    </div>
                    <div class="relative flex justify-center text-sm">
                        <span
                            class="bg-background-light px-4 text-[#4c669a] dark:bg-background-dark dark:text-gray-500">
                            Sudah punya akun?
                        </span>
                    </div>
                </div>

                <a class="flex w-full items-center justify-center gap-2 rounded-lg border border-[#cfd7e7] bg-white py-3.5 text-sm font-bold text-[#0d121b] transition-all hover:bg-gray-50 dark:border-gray-700 dark:bg-gray-800 dark:text-white dark:hover:bg-gray-750"
                    href="{{ route('login') }}">
                    Masuk ke Portal
                    <span class="material-symbols-outlined text-sm">arrow_forward</span>
                </a>

                <div class="flex items-center justify-center gap-2 pt-4">
                    <span class="material-symbols-outlined text-green-500 text-lg">verified</span>
                    <p class="text-xs text-[#4c669a] dark:text-gray-500">Enkripsi AES-256 Bit Aktif</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Script to handle URL Preview -->
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
    </script>
</body>

</html>