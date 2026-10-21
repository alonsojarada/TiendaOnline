<x-guest-layout>

<!-- Login compacto y adaptativo -->
<div class="w-full max-w-sm sm:max-w-md mx-auto px-3 sm:px-0 -mt-4 sm:mt-0">
    <div class="bg-white border border-slate-200/80 p-5 sm:p-8 rounded-3xl shadow-xl shadow-slate-200/40">

        <!-- Título Centrado -->
        <div class="mb-5 sm:mb-6 text-center">
            <h2 class="text-lg sm:text-xl font-black tracking-tight text-slate-900">
                Iniciar Sesión
            </h2>
        </div>

        <!-- Session Status -->
        <x-auth-session-status
            class="mb-4 text-xs font-medium"
            :status="session('status')"
        />

        <form method="POST" action="{{ route('login') }}" class="space-y-3.5 sm:space-y-4">
            @csrf

            <!-- Email Address -->
            <div class="space-y-1">
                <label
                    for="email"
                    class="text-[11px] font-bold uppercase tracking-wider text-slate-500"
                >
                    Correo Electrónico
                </label>

                <input
                    id="email"
                    class="block w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15 transition-all outline-none"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="tucorreo@dominio.com"
                />

                <x-input-error
                    :messages="$errors->get('email')"
                    class="mt-1 text-xs text-rose-500"
                />
            </div>

            <!-- Password -->
            <div class="space-y-1" x-data="{ showPassword: false }">
                <div class="flex items-center justify-between gap-2">
                    <label
                        for="password"
                        class="text-[11px] font-bold uppercase tracking-wider text-slate-500"
                    >
                        Contraseña
                    </label>

                    @if (Route::has('password.request'))
                        <a
                            class="text-[11px] sm:text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors whitespace-nowrap"
                            href="{{ route('password.request') }}"
                        >
                            ¿Olvidaste tu contraseña?
                        </a>
                    @endif
                </div>

                <!-- Contenedor del input y el botón del ojo -->
                <div class="relative">
                    <input
                        id="password"
                        :type="showPassword ? 'text' : 'password'"
                        class="block w-full pl-3.5 pr-10 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15 transition-all outline-none"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="••••••••"
                    />

                    <!-- Botón del Ojito -->
                    <button
                        type="button"
                        @click="showPassword = !showPassword"
                        class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-slate-600 focus:outline-none"
                    >
                        <!-- Icono Ojo Abierto (Visible) -->
                        <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>

                        <!-- Icono Ojo Cerrado (Oculto) -->
                        <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228365 3.65m0 0a3 3 0 104.243 4.243M9.878 9.878l4.242 4.242" />
                        </svg>
                    </button>
                </div>

                <x-input-error
                    :messages="$errors->get('password')"
                    class="mt-1 text-xs text-rose-500"
                />
            </div>

            <!-- Remember Me -->
            <div class="flex items-center pt-0.5">
                <label
                    for="remember_me"
                    class="inline-flex items-center gap-2 cursor-pointer select-none"
                >
                    <input
                        id="remember_me"
                        type="checkbox"
                        class="rounded border-slate-300 text-blue-600 shadow-sm focus:ring-blue-500 w-4 h-4"
                        name="remember"
                    >

                    <span class="text-xs text-slate-600 font-medium">
                        Recuérdame en este equipo
                    </span>
                </label>
            </div>

            <!-- Botón de Ingreso -->
            <div class="pt-1.5 sm:pt-2">
                <button
                    type="submit"
                    class="w-full inline-flex items-center justify-center gap-2 bg-blue-500 hover:bg-blue-600 active:scale-[0.99] text-white px-5 py-3 rounded-xl text-sm font-semibold shadow-md shadow-blue-500/20 transition-all duration-200"
                >
                    Acceder a SICOF
                </button>
            </div>

        </form>

    </div>
</div>

</x-guest-layout>