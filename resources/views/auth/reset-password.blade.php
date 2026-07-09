@php
    use App\Models\AppSetting;
    use Illuminate\Support\Facades\Cache;
    use Stancl\Tenancy\Database\TenantScope;

    $getSetting = function ($key, $default = null) {
        return Cache::rememberForever("global_app_setting_{$key}", function () use ($key, $default) {
            return AppSetting::withoutGlobalScope(TenantScope::class)
                ->whereNull('tenant_id')
                ->where('key', $key)
                ->value('value') ?? $default;
        });
    };

    $globalLogo = $getSetting('app_logo', null);
    $logo = $globalLogo ? asset($globalLogo) : asset('adminlte3/dist/img/AdminLTELogo.png');
    $appName = $getSetting('app_name', config('app.name'));
    $favicon = $getSetting('app_favicon', null) ? asset($getSetting('app_favicon', null)) : asset('favicon.ico');
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta content="width=device-width, initial-scale=1.0" name="viewport" />
    <title>Reset Password - {{ $appName }}</title>
    <link rel="icon" type="image/x-icon" href="{{ $favicon }}">
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:wght@300;400;500;600;700;800;900&amp;display=swap" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet" />
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
                    }
                },
            },
        }
    </script>
    <style>
        body { font-family: "Public Sans", sans-serif; }
    </style>
</head>

<body class="bg-background-light dark:bg-background-dark min-h-screen flex flex-col font-display">
    <header class="w-full px-6 py-4 lg:px-12 flex justify-between items-center bg-transparent">
        <div class="flex items-center gap-2">
            <div class="size-10 flex items-center justify-center">
                <img src="{{ $logo }}" alt="Logo" class="w-full h-full object-contain">
            </div>
            <h2 class="text-slate-900 dark:text-white text-xl font-bold tracking-tight">{{ $appName }}</h2>
        </div>
    </header>

    <main class="flex-1 flex flex-col items-center justify-center px-4 py-12">
        <div class="w-full max-w-[440px] bg-white dark:bg-slate-900 rounded-xl shadow-sm border border-slate-200 dark:border-slate-800 p-8 lg:p-10">
            <div class="flex justify-center mb-6">
                <div class="size-16 rounded-full bg-primary/10 flex items-center justify-center text-primary">
                    <span class="material-symbols-outlined text-4xl" style="font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 48">lock_reset</span>
                </div>
            </div>

            <div class="text-center mb-8">
                <h1 class="text-2xl lg:text-3xl font-bold text-slate-900 dark:text-white mb-3">Reset Password</h1>
                <p class="text-slate-600 dark:text-slate-400 text-sm lg:text-base leading-relaxed">
                    Silakan masukkan kata sandi baru Anda.
                </p>
            </div>

            <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                <!-- Email Address -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2" for="email">Email</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <span class="material-symbols-outlined text-xl">mail</span>
                        </div>
                        <input class="block w-full pl-10 pr-4 py-3 bg-white dark:bg-slate-800 border {{ $errors->has('email') ? 'border-red-500' : 'border-slate-300' }} dark:border-slate-700 rounded-lg text-slate-900 dark:text-white" id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required readonly autocomplete="username" />
                    </div>
                    @error('email') <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Password -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2" for="password">Password Baru</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <span class="material-symbols-outlined text-xl">lock</span>
                        </div>
                        <input class="block w-full pl-10 pr-4 py-3 bg-white dark:bg-slate-800 border {{ $errors->has('password') ? 'border-red-500' : 'border-slate-300' }} dark:border-slate-700 rounded-lg text-slate-900 dark:text-white" id="password" type="password" name="password" required autofocus autocomplete="new-password" />
                    </div>
                    @error('password') <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Confirm Password -->
                <div>
                    <label class="block text-sm font-bold text-slate-700 dark:text-slate-300 mb-2" for="password_confirmation">Konfirmasi Password</label>
                    <div class="relative group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-slate-400">
                            <span class="material-symbols-outlined text-xl">lock_reset</span>
                        </div>
                        <input class="block w-full pl-10 pr-4 py-3 bg-white dark:bg-slate-800 border {{ $errors->has('password_confirmation') ? 'border-red-500' : 'border-slate-300' }} dark:border-slate-700 rounded-lg text-slate-900 dark:text-white" id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" />
                    </div>
                    @error('password_confirmation') <p class="text-xs text-red-500 font-bold mt-1">{{ $message }}</p> @enderror
                </div>

                <button class="w-full bg-primary hover:bg-blue-700 text-white font-bold py-3 px-6 rounded-lg transition-all shadow-lg shadow-primary/30 flex items-center justify-center gap-2" type="submit">
                    <span>Simpan Password Baru</span>
                    <span class="material-symbols-outlined text-lg">save</span>
                </button>
            </form>
        </div>
    </main>

    <footer class="w-full py-8 px-6 mt-auto border-t border-slate-200 dark:border-slate-800">
        <div class="max-w-7xl mx-auto flex flex-col justify-center items-center gap-2 text-sm text-slate-500 dark:text-slate-400 font-medium text-center">
            <p>&copy; {{ date('Y') }} {{ $appName }}. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
