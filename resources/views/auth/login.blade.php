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
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Masuk - {{ $appName }}</title>
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
    <!-- Navigation Header -->
    <header
        class="flex items-center justify-between whitespace-nowrap border-b border-solid border-[#e7ebf3] dark:border-slate-800 bg-white dark:bg-slate-900 px-6 md:px-10 py-3">
        <div class="flex items-center gap-3 text-primary">
            <div class="size-8 flex items-center justify-center">
                <img src="{{ $logo }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <h2 class="text-[#0d121b] dark:text-white text-xl font-bold leading-tight tracking-[-0.015em]">
                {{ $appName }}</h2>
        </div>
        <div class="flex items-center gap-4">
            <button
                class="hidden sm:flex min-w-[84px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-4 bg-primary/10 text-primary text-sm font-bold leading-normal">
                <span class="truncate">Akses Institusi</span>
            </button>
            <button
                class="flex min-w-[40px] cursor-pointer items-center justify-center overflow-hidden rounded-lg h-10 px-2 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                <span class="material-symbols-outlined">help</span>
            </button>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 flex items-center justify-center p-4 md:p-8">
        <div
            class="w-full max-w-[480px] bg-white dark:bg-slate-900 rounded-xl shadow-xl border border-slate-200 dark:border-slate-800 overflow-hidden">
            <!-- Welcome Section -->
            <div class="p-8 pb-4">
                <h1 class="text-[#0d121b] dark:text-white text-3xl font-extrabold leading-tight tracking-tight mb-2">
                    Masuk ke {{ $appName }}</h1>
                <p class="text-slate-500 dark:text-slate-400 text-base">Akses arsip dan data akademik sekolah Anda.</p>
            </div>

            <!-- Role Switcher (Hidden Input) -->
            <input type="hidden" id="selected_role" value="{{ $isTenant ? 'school' : 'dinas' }}">

            <!-- Role Tabs -->
            <div class="px-8 pb-2">
                <div class="flex p-1 bg-slate-100 dark:bg-slate-800 rounded-lg">
                    <button type="button" id="btn_dinas"
                        class="flex-1 py-2 text-sm font-bold rounded-md transition-all text-slate-500 hover:text-slate-700 dark:text-slate-400"
                        onclick="selectRole('dinas')">
                        Dinas Pendidikan
                    </button>
                    <button type="button" id="btn_school"
                        class="flex-1 py-2 text-sm font-bold rounded-md transition-all text-slate-500 hover:text-slate-700 dark:text-slate-400"
                        onclick="selectRole('school')">
                        Sekolah / Operator
                    </button>
                </div>
            </div>

            <!-- Alerts -->
            <div id="alert_central_school" class="mx-8 mt-4 p-3 bg-blue-50 text-blue-700 rounded-lg text-sm hidden">
                <span class="font-bold">Info:</span> Masukkan Kode Sekolah untuk dialihkan ke portal login sekolah.
            </div>
            <div id="alert_tenant_dinas" class="mx-8 mt-4 p-3 bg-amber-50 text-amber-700 rounded-lg text-sm hidden">
                <span class="font-bold">Perhatian:</span> Akun Dinas login melalui Portal Pusat. Anda akan dialihkan.
            </div>

            <!-- Login Form -->
            <form class="p-8 pt-4 space-y-6" id="loginForm" method="POST"
                action="{{ $isTenant && Route::has('tenant.login') ? route('tenant.login', ['tenant' => tenant('id')]) : route('login') }}">
                @csrf

                <!-- School ID Field (Dynamic) -->
                <div class="space-y-2 hidden" id="school_selector_group">
                    <label class="text-[#0d121b] dark:text-slate-200 text-sm font-semibold leading-normal">Kode
                        Sekolah</label>
                    <div class="relative">
                        <input type="text" id="school_id"
                            class="w-full rounded-lg border border-slate-300 dark:border-slate-700 bg-white dark:bg-slate-800 text-[#0d121b] dark:text-white h-12 px-4 focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none transition-all placeholder:text-slate-400"
                            placeholder="Contoh: smpn1" autocomplete="off" />
                        <p class="text-xs text-slate-500 mt-1">Masukkan kode sekolah untuk masuk ke portal sekolah.</p>
                    </div>
                </div>

                <!-- Email Field -->
                <div class="space-y-2">
                    <label class="text-[#0d121b] dark:text-slate-200 text-sm font-semibold leading-normal">Email /
                        Username</label>
                    <div class="relative">
                        <input type="email" name="email" id="email" value="{{ old('email') ?? request('email') }}"
                            class="w-full rounded-lg border {{ $errors->has('email') ? 'border-red-500' : 'border-slate-300' }} dark:border-slate-700 bg-white dark:bg-slate-800 text-[#0d121b] dark:text-white h-12 px-4 focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none transition-all placeholder:text-slate-400"
                            placeholder="admin@sekolah.sch.id" required />
                    </div>
                    @error('email')
                        <p class="text-xs text-red-500">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Field -->
                <div class="space-y-2">
                    <div class="flex justify-between items-center">
                        <label
                            class="text-[#0d121b] dark:text-slate-200 text-sm font-semibold leading-normal">Password</label>
                        <a class="text-primary text-sm font-semibold hover:underline"
                            href="{{ route('password.request') }}">Lupa Password?</a>
                    </div>
                    <div class="relative flex items-center">
                        <input type="password" name="password" id="password"
                            class="w-full rounded-lg border {{ $errors->has('password') ? 'border-red-500' : 'border-slate-300' }} dark:border-slate-700 bg-white dark:bg-slate-800 text-[#0d121b] dark:text-white h-12 px-4 pr-12 focus:ring-2 focus:ring-primary/50 focus:border-primary outline-none transition-all placeholder:text-slate-400"
                            placeholder="••••••••" required />
                        <button class="absolute right-3 text-slate-400 hover:text-slate-600 dark:hover:text-slate-200"
                            type="button" onclick="togglePassword()">
                            <span class="material-symbols-outlined" id="eyeIcon">visibility</span>
                        </button>
                    </div>
                    @error('password')
                        <p class="text-xs text-red-500">{{ $message }}</p>
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
                <div class="space-y-3 pt-2">
                    <button
                        class="w-full bg-primary hover:bg-primary/90 text-white font-bold py-3.5 rounded-lg transition-colors shadow-lg shadow-primary/20 flex items-center justify-center gap-2"
                        type="submit">
                        <span>Masuk</span>
                        <span class="material-symbols-outlined text-xl">login</span>
                    </button>

                    @if(config('app.env') === 'local' && isset($demoUsers))
                        <div class="grid grid-cols-2 gap-2 mt-2">
                            <button type="button"
                                onclick="$('#email').val('{{ $demoUsers['dinas']->email ?? '' }}');$('#password').val('password');selectRole('dinas')"
                                class="text-xs bg-gray-100 p-2 rounded text-gray-600 hover:bg-gray-200">Demo Dinas</button>
                            <button type="button"
                                onclick="$('#email').val('{{ $demoUsers['school_admin']->email ?? '' }}');$('#password').val('password');selectRole('school')"
                                class="text-xs bg-gray-100 p-2 rounded text-gray-600 hover:bg-gray-200">Demo
                                Sekolah</button>
                        </div>
                    @endif
                </div>
            </form>

            <!-- Card Footer -->
            @if(!$isTenant)
                <div
                    class="px-8 py-6 bg-slate-50 dark:bg-slate-800/50 border-t border-slate-100 dark:border-slate-800 flex justify-center text-sm">
                    <p class="text-slate-500 dark:text-slate-400">
                        Sekolah baru?
                        <a class="text-primary font-bold hover:underline" href="{{ route('register') }}">Daftarkan
                            Sekolah</a>
                    </p>
                </div>
            @endif
        </div>
    </main>

    <!-- Page Footer -->
    <footer class="p-6 text-center">
        <p class="text-slate-400 dark:text-slate-600 text-xs">
            © {{ date('Y') }} {{ $appName }}. Secure Institutional Information Systems.
        </p>
    </footer>

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