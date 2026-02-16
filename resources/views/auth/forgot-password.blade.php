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
    <title>Lupa Password - {{ $appName }}</title>
    <link rel="icon" type="image/x-icon"
        href="{{ !empty($central_branding['app_favicon']) ? asset($central_branding['app_favicon']) : asset('favicon.ico') }}">
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

        .hidden {
            display: none;
        }

        .input-otp {
            letter-spacing: 0.5em;
            text-align: center;
            font-size: 1.25rem;
        }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark min-h-screen flex flex-col font-display">
    <!-- Top Navigation Bar (Branding only) -->
    <header class="w-full px-6 py-4 lg:px-12 flex justify-between items-center bg-transparent">
        <div class="flex items-center gap-2">
            <div class="size-10 flex items-center justify-center">
                <img src="{{ $logo }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <h2 class="text-slate-900 dark:text-white text-xl font-bold tracking-tight">{{ $appName }}</h2>
        </div>
    </header>

    <!-- Main Content Area -->
    <main class="flex-1 flex flex-col items-center justify-center px-4 py-12">
        <div
            class="w-full max-w-[440px] bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-8 lg:p-10 relative">

            <!-- Loading Overlay -->
            <div id="loadingOverlay"
                class="absolute inset-0 bg-white/80 dark:bg-slate-900/80 z-50 rounded-xl flex items-center justify-center hidden">
                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-primary"></div>
            </div>

            <!-- Icon Representation -->
            <div class="flex justify-center mb-6">
                <div class="size-16 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-4xl" id="methodIcon"
                        style="font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 48">lock_reset</span>
                </div>
            </div>

            <!-- Header & Instructions -->
            <div class="text-center mb-8">
                <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white mb-3" id="pageTitle">Lupa Kata
                    Sandi?</h1>
                <p class="text-slate-600 dark:text-slate-400 text-sm lg:text-base leading-relaxed" id="pageDesc">
                    Pilih metode pemulihan kata sandi Anda.
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <!-- Method Switcher -->
            <div class="flex p-1 bg-slate-100 dark:bg-slate-800 rounded-lg mb-6" id="methodSwitcher">
                <button type="button" onclick="switchMethod('email')" id="btnEmail"
                    class="flex-1 py-2 text-sm font-bold rounded-md bg-white shadow-sm text-primary transition-all">
                    Email
                </button>
                <button type="button" onclick="switchMethod('whatsapp')" id="btnWhatsapp"
                    class="flex-1 py-2 text-sm font-bold rounded-md text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-all">
                    WhatsApp (OTP)
                </button>
            </div>

            <!-- EMAIL FORM -->
            <form action="{{ route('password.email') }}" method="POST" class="space-y-6" id="formEmail">
                @csrf
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2" for="email">Alamat
                        Email</label>
                    <div class="relative group">
                        <div
                            class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                            <span class="material-symbols-outlined text-xl">mail</span>
                        </div>
                        <input
                            class="block w-full pl-10 pr-4 py-3 bg-white dark:bg-slate-800 border {{ $errors->has('email') ? 'border-red-500' : 'border-slate-300' }} dark:border-slate-700 rounded-lg text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm lg:text-base font-medium"
                            id="email" name="email" value="{{ old('email') }}" placeholder="contoh@sekolah.sch.id"
                            required type="email" />
                    </div>
                    @error('email')
                        <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p>
                    @enderror
                </div>
                <button
                    class="w-full bg-primary hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all shadow-lg shadow-primary/30 flex items-center justify-center gap-2"
                    type="submit">
                    <span>Kirim Link Reset</span>
                    <span class="material-symbols-outlined text-lg">arrow_forward</span>
                </button>
            </form>

            <!-- WHATSAPP OTP FORM -->
            <div id="formWhatsapp" class="hidden space-y-6">

                <!-- Step 1: Request OTP -->
                <div id="stepRequestOtp">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2"
                            for="wa_number">Nomor WhatsApp</label>
                        <p class="text-xs text-slate-500 mb-2">Masukkan nomor WhatsApp yang terdaftar untuk menerima
                            OTP.</p>
                        <div class="relative group">
                            <div
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400 group-focus-within:text-primary transition-colors">
                                <span class="material-symbols-outlined text-xl">call</span>
                            </div>
                            <input
                                class="block w-full pl-10 pr-4 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white placeholder:text-slate-400 focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all text-sm lg:text-base font-medium"
                                id="wa_number" placeholder="081234567890" type="text" inputmode="numeric" />
                        </div>
                        <p id="otpError" class="text-xs text-red-500 font-bold mt-1 hidden"></p>
                    </div>
                    <button onclick="requestOtp()"
                        class="w-full mt-4 bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-lg transition-all shadow-lg shadow-green-600/30 flex items-center justify-center gap-2">
                        <span>Kirim Kode OTP</span>
                        <span class="material-symbols-outlined text-lg">chat</span>
                    </button>
                </div>

                <!-- Step 2: Verify OTP -->
                <div id="stepVerifyOtp" class="hidden">
                    <div class="text-center mb-4">
                        <p class="text-sm text-slate-600 dark:text-slate-400">Kode OTP telah dikirim ke WhatsApp Anda.
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Kode OTP</label>
                        <input
                            class="block w-full py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all input-otp font-mono"
                            id="otpInput" placeholder="123456" maxlength="6" type="text" />
                        <p id="verifyError" class="text-xs text-red-500 font-bold mt-1 hidden"></p>
                    </div>
                    <button onclick="verifyOtp()"
                        class="w-full mt-4 bg-primary hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all shadow-lg shadow-primary/30 flex items-center justify-center gap-2">
                        <span>Verifikasi OTP</span>
                        <span class="material-symbols-outlined text-lg">check_circle</span>
                    </button>
                    <div class="mt-4 text-center">
                        <button onclick="requestOtp()" class="text-xs text-primary hover:underline font-bold">Kirim
                            Ulang OTP</button>
                    </div>
                </div>

                <!-- Step 3: Reset Password -->
                <div id="stepResetPassword" class="hidden space-y-4">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Password
                            Baru</label>
                        <div class="relative group">
                            <span
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400"><span
                                    class="material-symbols-outlined">lock</span></span>
                            <input
                                class="block w-full pl-10 pr-4 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                id="newPassword" type="password" placeholder="Min. 8 karakter" />
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2">Konfirmasi
                            Password</label>
                        <div class="relative group">
                            <span
                                class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400"><span
                                    class="material-symbols-outlined">lock_reset</span></span>
                            <input
                                class="block w-full pl-10 pr-4 py-3 bg-white dark:bg-slate-800 border border-slate-300 dark:border-slate-700 rounded-lg text-slate-900 dark:text-white focus:outline-none focus:ring-2 focus:ring-primary/20 focus:border-primary transition-all"
                                id="confirmPassword" type="password" placeholder="Ulangi password" />
                        </div>
                        <p id="resetError" class="text-xs text-red-500 font-bold mt-1 hidden"></p>
                    </div>
                    <button onclick="resetPassword()"
                        class="w-full bg-primary hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all shadow-lg shadow-primary/30 flex items-center justify-center gap-2">
                        <span>Simpan Password Baru</span>
                        <span class="material-symbols-outlined text-lg">save</span>
                    </button>
                </div>

            </div>

            <!-- Back to Login -->
            <div class="mt-8 pt-6 border-t border-slate-100 dark:border-slate-800 text-center">
                <a class="inline-flex items-center gap-1 text-primary hover:text-blue-700 font-bold text-sm transition-colors group"
                    href="{{ route('login') }}">
                    <span
                        class="material-symbols-outlined text-lg group-hover:-translate-x-1 transition-transform">chevron_left</span>
                    <span>Kembali ke Login</span>
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="w-full py-8 px-6 mt-auto">
        <div
            class="max-w-7xl mx-auto flex flex-col md:flex-row justify-center items-center gap-6 text-sm text-slate-500 dark:text-slate-400 font-medium">
            <div class="flex items-center gap-8">
                <a class="hover:text-primary transition-colors" href="{{ $privacyLink }}">Privacy Policy</a>
                <a class="hover:text-primary transition-colors" href="{{ $termsLink }}">Terms of Service</a>
            </div>
            <div class="md:ml-auto">
                <p>&copy; {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <!-- Logic Script -->
    <script>
        // Constants & State
        const routes = {
            sendOtp: "{{ route('password.otp.send') }}",
            verifyOtp: "{{ route('password.otp.verify') }}",
            resetPassword: "{{ route('password.otp.update') }}"
        };
        const csrfToken = "{{ csrf_token() }}";
        let currentEmail = '';

        function switchMethod(method) {
            const btnEmail = document.getElementById('btnEmail');
            const btnWhatsapp = document.getElementById('btnWhatsapp');
            const formEmail = document.getElementById('formEmail');
            const formWhatsapp = document.getElementById('formWhatsapp');
            const pageDesc = document.getElementById('pageDesc');
            const methodIcon = document.getElementById('methodIcon');

            if (method === 'email') {
                btnEmail.className = "flex-1 py-2 text-sm font-bold rounded-md bg-white shadow-sm text-primary transition-all";
                btnWhatsapp.className = "flex-1 py-2 text-sm font-bold rounded-md text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-all";
                formEmail.classList.remove('hidden');
                formWhatsapp.classList.add('hidden');
                pageDesc.innerText = "Masukkan alamat email yang terdaftar dan kami akan mengirimkan link untuk mereset kata sandi Anda.";
                methodIcon.innerText = "lock_reset";
                methodIcon.parentElement.className = "size-16 rounded-full bg-primary/10 flex items-center justify-center text-primary";
            } else {
                btnWhatsapp.className = "flex-1 py-2 text-sm font-bold rounded-md bg-white shadow-sm text-green-600 transition-all";
                btnEmail.className = "flex-1 py-2 text-sm font-bold rounded-md text-slate-500 hover:text-slate-700 dark:hover:text-slate-300 transition-all";
                formEmail.classList.add('hidden');
                formWhatsapp.classList.remove('hidden');
                pageDesc.innerText = "Dapatkan kode OTP melalui WhatsApp untuk mereset kata sandi Anda dengan cepat.";
                methodIcon.innerText = "chat";
                methodIcon.parentElement.className = "size-16 rounded-full bg-green-100 flex items-center justify-center text-green-600";
            }
        }

        async function requestOtp() {
            const email = document.getElementById('wa_email').value;
            const errorEl = document.getElementById('otpError');

            if (!email) {
                errorEl.innerText = "Email wajib diisi.";
                errorEl.classList.remove('hidden');
                return;
            }

            toggleLoading(true);
            try {
                const res = await fetch(routes.sendOtp, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ email })
                });
                const data = await res.json();

                if (res.ok) {
                    currentEmail = email;
                    document.getElementById('stepRequestOtp').classList.add('hidden');
                    document.getElementById('stepVerifyOtp').classList.remove('hidden');
                    alert(data.message);
                } else {
                    errorEl.innerText = data.message || "Terjadi kesalahan.";
                    errorEl.classList.remove('hidden');
                }
            } catch (e) {
                errorEl.innerText = "Gagal menghubungi server.";
                errorEl.classList.remove('hidden');
            } finally {
                toggleLoading(false);
            }
        }

        async function verifyOtp() {
            const otp = document.getElementById('otpInput').value;
            const errorEl = document.getElementById('verifyError');

            if (!otp || otp.length !== 6) {
                errorEl.innerText = "Masukkan 6 digit kode OTP.";
                errorEl.classList.remove('hidden');
                return;
            }

            toggleLoading(true);
            try {
                const res = await fetch(routes.verifyOtp, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ email: currentEmail, otp })
                });
                const data = await res.json();

                if (res.ok) {
                    document.getElementById('stepVerifyOtp').classList.add('hidden');
                    document.getElementById('stepResetPassword').classList.remove('hidden');
                } else {
                    errorEl.innerText = data.message || "OTP Salah.";
                    errorEl.classList.remove('hidden');
                }
            } catch (e) {
                errorEl.innerText = "Gagal verifikasi.";
                errorEl.classList.remove('hidden');
            } finally {
                toggleLoading(false);
            }
        }

        async function resetPassword() {
            const password = document.getElementById('newPassword').value;
            const confirm = document.getElementById('confirmPassword').value;
            const otp = document.getElementById('otpInput').value; // Need OTP for validation
            const errorEl = document.getElementById('resetError');

            if (password.length < 8) {
                errorEl.innerText = "Password minimal 8 karakter.";
                errorEl.classList.remove('hidden');
                return;
            }

            if (password !== confirm) {
                errorEl.innerText = "Konfirmasi password tidak cocok.";
                errorEl.classList.remove('hidden');
                return;
            }

            toggleLoading(true);
            try {
                const res = await fetch(routes.resetPassword, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken },
                    body: JSON.stringify({ email: currentEmail, otp, password, password_confirmation: confirm })
                });
                const data = await res.json();

                if (res.ok) {
                    alert('Password berhasil direset! Silakan login.');
                    window.location.href = "{{ route('login') }}";
                } else {
                    errorEl.innerText = data.message || "Gagal reset password.";
                    errorEl.classList.remove('hidden');
                }
            } catch (e) {
                errorEl.innerText = "Gagal menghubungi server.";
                errorEl.classList.remove('hidden');
            } finally {
                toggleLoading(false);
            }
        }

        function toggleLoading(show) {
            const overlay = document.getElementById('loadingOverlay');
            if (show) overlay.classList.remove('hidden');
            else overlay.classList.add('hidden');
        }
    </script>
</body>

</html>