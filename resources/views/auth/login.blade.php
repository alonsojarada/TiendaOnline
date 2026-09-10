<x-guest-layout>

    <!-- Contenedor del Login Compacto -->
    <div class="w-full bg-white border border-slate-200/80 p-6 sm:p-8 rounded-3xl shadow-xl shadow-slate-200/40">

        <!-- Título Centrado -->
        <div class="mb-6 text-center">
            <h2 class="text-xl font-black tracking-tight text-slate-900">Iniciar Sesión</h2>
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4 text-xs font-medium" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div class="space-y-1">
                <label for="email" class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Correo
                    Electrónico</label>
                <input id="email"
                    class="block w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15 transition-all outline-none"
                    type="email" name="email" :value="old('email')" required autofocus autocomplete="username"
                    placeholder="tucorreo@dominio.com" />
                <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-500" />
            </div>

            <!-- Password -->
            <div class="space-y-1">
                <div class="flex items-center justify-between">
                    <label for="password"
                        class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Contraseña</label>

                    @if (Route::has('password.request'))
                        <a class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors"
                            href="{{ route('password.request') }}">
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                <input id="password"
                    class="block w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15 transition-all outline-none"
                    type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />

                <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-500" />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center pt-1">
                <label for="remember_me" class="inline-flex items-center gap-2 cursor-pointer select-none">
                    <input id="remember_me" type="checkbox"
                        class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 w-4 h-4"
                        name="remember">
                    <span class="text-xs text-slate-600 font-medium">Recuérdame en este equipo</span>
                </label>
            </div>

            <!-- Botón de Ingreso -->
            <div class="pt-2">
                <button type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 bg-blue-500 hover:bg-blue-600 text-white px-5 py-3 rounded-xl text-sm font-semibold shadow-md shadow-blue-500/20 transition-all duration-200">
                    Acceder a SICOF
                </button>
            </div>
        </form>

    </div>

</x-guest-layout>