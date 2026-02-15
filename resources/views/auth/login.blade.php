@php
    $dinas_logo = \Illuminate\Support\Facades\Cache::get('dinas_app_logo');
    // Determine context
    $isTenant = tenant() ? true : false;

    if ($isTenant) {
        $logo = tenant('logo') ? tenant_asset(tenant('logo')) : ($dinas_logo ?? asset('adminlte/dist/img/AdminLTELogo.png'));
        $appName = tenant('nama_sekolah');
    } else {
        $logo = $dinas_logo ?? asset('adminlte/dist/img/AdminLTELogo.png');
        $appName = \App\Models\AppSetting::getSetting('app_name', config('app.name'));
    }

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
    <title>Masuk - {{ $appName }}</title>
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

        /* Custom Tab Styles */
        .role-tab-active {
            background-color: white;
            color: #135bec;
            box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
        }

        .dark .role-tab-active {
            background-color: #334155;
            color: white;
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

        <!-- Right Side (Login Form) -->
        <div
            class="w-full lg:w-1/2 flex items-center justify-center p-6 md:p-12 bg-white dark:bg-slate-900 overflow-y-auto">
            <div class="w-full max-w-[420px]">

                <!-- Mobile Logo (Visible only on small screens) -->
                <div class="lg:hidden mb-8 flex items-center gap-2">
                    <img src="{{ $logo }}" alt="Logo" class="h-10 w-auto">
                    <span class="text-xl font-bold text-slate-900 dark:text-white">{{ $appName }}</span>
                </div>

                <!-- Welcome Section -->
                <div class="mb-8">
                    <h1
                        class="text-[#0d121b] dark:text-white text-3xl font-extrabold leading-tight tracking-tight mb-2">
                        Selamat Datang! 👋</h1>
                    <p class="text-slate-500 dark:text-slate-400 text-base">Silakan masuk ke akun Anda untuk
                        melanjutkan.</p>
                </div>

                <!-- Role Switcher (Hidden Input) -->
                <input type="hidden" id="selected_role" value="{{ $isTenant ? 'school' : 'dinas' }}">

                <!-- Role Tabs -->
                <div class="mb-6">
                    <div class="flex p-1 bg-slate-100 dark:bg-slate-800 rounded-xl">
                        <button type="button" id="btn_dinas"
                            class="flex-1 py-2.5 text-sm font-bold rounded-lg transition-all text-slate-500 hover:text-slate-700 dark:text-slate-400"
                            onclick="selectRole('dinas')">
                            Dinas Pendidikan
                        </button>
                        <button type="button" id="btn_school"
                            class="flex-1 py-2.5 text-sm font-bold rounded-lg transition-all text-slate-500 hover:text-slate-700 dark:text-slate-400"
                            onclick="selectRole('school')">
                            Sekolah / Operator
                        </button>
                    </div>
                </div>

                <!-- Alerts -->
                <div id="alert_central_school"
                    class="mb-6 p-4 bg-blue-50 text-blue-700 rounded-xl text-sm hidden border border-blue-100 flex items-start gap-3">
                    <span class="material-symbols-outlined text-lg mt-0.5">info</span>
                    <div>
                        <span class="font-bold block mb-1">Portal Sekolah</span>
                        Masukkan Kode Sekolah Anda untuk dialihkan ke halaman login khusus sekolah Anda.
                    </div>
                </div>
                <div id="alert_tenant_dinas"
                    class="mb-6 p-4 bg-amber-50 text-amber-700 rounded-xl text-sm hidden border border-amber-100 flex items-start gap-3">
                    <span class="material-symbols-outlined text-lg mt-0.5">warning</span>
                    <div>
                        <span class="font-bold block mb-1">Akses Dinas</span>
                        Akun Dinas Login melalui Portal Pusat. Anda akan dialihkan.
                    </div>
                </div>

                <!-- Login Form -->
                <form class="space-y-5" id="loginForm" method="POST"
                    action="{{ $isTenant && Route::has('tenant.login') ? route('tenant.login', ['tenant' => tenant('id')]) : route('login') }}">
                    @csrf

                    <!-- School ID Field (Dynamic) -->
                    <div class="space-y-2 hidden animate-fade-in-down" id="school_selector_group">
                        <label class="text-[#0d121b] dark:text-slate-200 text-sm font-bold">Kode Sekolah</label>
                        <div class="relative">
                            <span
                                class="absolute left-4 top-3.5 text-slate-400 material-symbols-outlined text-[20px]">domain</span>
                            <input type="text" id="school_id"
                                class="w-full rounded-xl border border-slate-200 dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-[#0d121b] dark:text-white h-12 pl-12 pr-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-slate-400 font-medium"
                                placeholder="Contoh: smpn1" autocomplete="off" />
                        </div>
                    </div>

                    <!-- Email Field -->
                    <div class="space-y-2">
                        <label class="text-[#0d121b] dark:text-slate-200 text-sm font-bold">Email / Username</label>
                        <div class="relative">
                            <span
                                class="absolute left-4 top-3.5 text-slate-400 material-symbols-outlined text-[20px]">mail</span>
                            <input type="email" name="email" id="email" value="{{ old('email') ?? request('email') }}"
                                class="w-full rounded-xl border {{ $errors->has('email') ? 'border-red-500' : 'border-slate-200' }} dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-[#0d121b] dark:text-white h-12 pl-12 pr-4 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-slate-400 font-medium"
                                placeholder="nama@email.com" required />
                        </div>
                        @error('email')
                            <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="space-y-2">
                        <div class="flex justify-between items-center">
                            <label class="text-[#0d121b] dark:text-slate-200 text-sm font-bold">Password</label>
                            <a class="text-primary text-sm font-bold hover:underline"
                                href="{{ route('password.request') }}">Lupa Password?</a>
                        </div>
                        <div class="relative">
                            <span
                                class="absolute left-4 top-3.5 text-slate-400 material-symbols-outlined text-[20px]">lock</span>
                            <input type="password" name="password" id="password"
                                class="w-full rounded-xl border {{ $errors->has('password') ? 'border-red-500' : 'border-slate-200' }} dark:border-slate-700 bg-slate-50 dark:bg-slate-800 text-[#0d121b] dark:text-white h-12 pl-12 pr-12 focus:ring-2 focus:ring-primary/20 focus:border-primary outline-none transition-all placeholder:text-slate-400 font-medium"
                                placeholder="••••••••" required />
                            <button
                                class="absolute right-4 top-3.5 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 transition-colors"
                                type="button" onclick="togglePassword()">
                                <span class="material-symbols-outlined text-[20px]" id="eyeIcon">visibility</span>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center gap-3">
                        <input
                            class="size-5 rounded border-slate-300 dark:border-slate-700 text-primary focus:ring-primary/20 cursor-pointer"
                            id="remember" name="remember" type="checkbox" />
                        <label class="text-slate-600 dark:text-slate-400 text-sm font-medium cursor-pointer"
                            for="remember">Ingat saya</label>
                    </div>

                    <!-- Action Buttons -->
                    <div class="space-y-4 pt-2">
                        <button
                            class="w-full bg-primary hover:bg-blue-700 text-white font-bold py-4 rounded-xl transition-all shadow-lg shadow-primary/30 hover:shadow-primary/50 flex items-center justify-center gap-2 transform active:scale-[0.98]"
                            type="submit">
                            <span>Masuk Sekarang</span>
                            <span class="material-symbols-outlined text-xl">login</span>
                        </button>


                    </div>
                </form>

                <!-- Footer Links -->
                @if(!$isTenant)
                    <div class="mt-8 text-center">
                        <p class="text-slate-500 dark:text-slate-400 text-sm">
                            Belum mendaftarkan sekolah?
                            <a class="text-primary font-bold hover:underline ml-1" href="{{ route('register') }}">Daftar
                                Sekarang</a>
                        </p>
                    </div>
                @endif
            </div>
        </div>
    </main>



    <!-- Logic Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const isTenant = {{ $isTenant ? 'true' : 'false' }};
            const roleInput = document.getElementById('selected_role');
            const schoolGroup = document.getElementById('school_selector_group');
            const alertCentral = document.getElementById('alert_central_school');
            const alertTenant = document.getElementById('alert_tenant_dinas');
            const loginForm = document.getElementById('loginForm');
            const schoolIdInput = document.getElementById('school_id');
            const btnDinas = document.getElementById('btn_dinas');
            const btnSchool = document.getElementById('btn_school');

            window.selectRole = function (role) {
                roleInput.value = role;

                // Update UI Style
                if (role === 'dinas') {
                    btnDinas.classList.add('role-tab-active');
                    btnSchool.classList.remove('role-tab-active');
                } else {
                    btnSchool.classList.add('role-tab-active');
                    btnDinas.classList.remove('role-tab-active');
                }

                // Visibility Logic
                if (role === 'school' && !isTenant) {
                    // Central -> School Redirect
                    schoolGroup.classList.remove('hidden');
                    alertCentral.classList.remove('hidden');
                    alertTenant.classList.add('hidden');
                    if (schoolIdInput) schoolIdInput.required = true;
                } else if (role === 'dinas' && isTenant) {
                    // Tenant -> Central Redirect
                    schoolGroup.classList.add('hidden');
                    alertCentral.classList.add('hidden');
                    alertTenant.classList.remove('hidden');
                    if (schoolIdInput) schoolIdInput.required = false;
                } else {
                    // Normal
                    schoolGroup.classList.add('hidden');
                    alertCentral.classList.add('hidden');
                    alertTenant.classList.add('hidden');
                    if (schoolIdInput) schoolIdInput.required = false;
                }
            };

            // Init
            selectRole(isTenant ? 'school' : 'dinas');

            // Form Submit Handler
            if (loginForm) {
                loginForm.addEventListener('submit', function (e) {
                    const role = roleInput.value;
                    const email = document.getElementById('email').value;

                    // LOGIC 1: Central -> School Redirect (Path Based)
                    if (role === 'school' && !isTenant) {
                        e.preventDefault();
                        let schoolId = schoolIdInput.value.trim();
                        if (!schoolId) {
                            alert('Harap masukkan Kode Sekolah.');
                            return;
                        }
                        schoolId = schoolId.replace(/[^a-zA-Z0-9-_]/g, '');
                        // Construct Tenant Login URL: base_url/school_id/login
                        const protocol = window.location.protocol;
                        const host = window.location.host;
                        loginForm.action = `${protocol}//${host}/${schoolId}/login`;
                        loginForm.submit();
                        return;
                    }

                    // LOGIC 2: School -> Central Redirect
                    if (role === 'dinas' && isTenant) {
                        e.preventDefault();
                        const protocol = window.location.protocol;
                        const host = window.location.host;
                        // Redirect to root login (central)
                        window.location.href = `${protocol}//${host}/login?email=${encodeURIComponent(email)}`;
                        return;
                    }
                });
            }
        });

        function togglePassword() {
            const input = document.getElementById('password');
            const icon = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.innerText = 'visibility_off';
            } else {
                input.type = 'password';
                icon.innerText = 'visibility';
            }
        }
    </script>

    <!-- jQuery for Demo buttons only -->
    @if(config('app.env') === 'local')
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @endif
</body>

</html>