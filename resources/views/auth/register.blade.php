<x-guest-layout>

    <!-- Contenedor de Registro Compacto con ancho adaptativo en móviles -->
    <div class="w-full max-w-sm sm:max-w-md mx-auto px-3 sm:px-0">
        <div class="bg-white border border-slate-200/80 p-5 sm:p-8 rounded-3xl shadow-xl shadow-slate-200/40">

            <!-- Título Centrado -->
            <div class="mb-5 sm:mb-6 text-center">
                <h2 class="text-lg sm:text-xl font-black tracking-tight text-slate-900">Crear Cuenta</h2>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-3.5 sm:space-y-4">
                @csrf

                <!-- Name -->
                <div class="space-y-1">
                    <label for="name" class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Nombre Completo</label>
                    <input id="name"
                        class="block w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15 transition-all outline-none"
                        type="text" name="name" :value="old('name')" required autofocus autocomplete="name"
                        placeholder="Tu Nombre" />
                    <x-input-error :messages="$errors->get('name')" class="mt-1 text-xs text-rose-500" />
                </div>

                <!-- Email Address -->
                <div class="space-y-1">
                    <label for="email" class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Correo Electrónico</label>
                    <input id="email"
                        class="block w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15 transition-all outline-none"
                        type="email" name="email" :value="old('email')" required autocomplete="username"
                        placeholder="tucorreo@dominio.com" />
                    <x-input-error :messages="$errors->get('email')" class="mt-1 text-xs text-rose-500" />
                </div>

                <!-- Password -->
                <div class="space-y-1">
                    <label for="password" class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Contraseña</label>
                    <input id="password"
                        class="block w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15 transition-all outline-none"
                        type="password" name="password" required autocomplete="new-password" placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password')" class="mt-1 text-xs text-rose-500" />
                </div>

                <!-- Confirm Password -->
                <div class="space-y-1">
                    <label for="password_confirmation" class="text-[11px] font-bold uppercase tracking-wider text-slate-500">Confirmar Contraseña</label>
                    <input id="password_confirmation"
                        class="block w-full px-3.5 py-2.5 bg-slate-50/70 border border-slate-200 rounded-xl text-sm text-slate-800 focus:bg-white focus:border-blue-500 focus:ring-2 focus:ring-blue-500/15 transition-all outline-none"
                        type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" />
                    <x-input-error :messages="$errors->get('password_confirmation')" class="mt-1 text-xs text-rose-500" />
                </div>

                <!-- Acciones (Enlace e Ingreso) -->
                <div class="flex items-center justify-between pt-2">
                    <a class="text-xs font-semibold text-blue-600 hover:text-blue-700 transition-colors" href="{{ route('login') }}">
                        ¿Ya estás registrado?
                    </a>

                    <button type="submit"
                        class="inline-flex items-center justify-center gap-2 bg-blue-500 hover:bg-blue-600 active:scale-[0.99] text-white px-5 py-2.5 rounded-xl text-sm font-semibold shadow-md shadow-blue-500/20 transition-all duration-200">
                        Registrarse
                    </button>
                </div>
            </form>

        </div>
    </div>

</x-guest-layout>