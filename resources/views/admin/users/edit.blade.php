<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('Editar Usuario') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <form method="POST" action="{{ route('usuarios.update', $user->id) }}"
                    class="max-w-md mx-auto space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Nombre -->
                    <div>
                        <x-input-label for="name" :value="__('Nombre')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $user->name)" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Email -->
                    <div class="mt-4">
                        <x-input-label for="email" :value="__('Correo Electrónico')" />
                        <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                            :value="old('email', $user->email)" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Empresa Asignada -->
                    <div class="mt-4">
                        <x-input-label for="company_id" :value="__('Empresa Asignada')" />
                        <select id="company_id" name="company_id" required
                            class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <option value="">Seleccione una empresa...</option>
                            @foreach($companies as $company)
                                <option value="{{ $company->id }}" {{ old('company_id', $user->company_id) == $company->id ? 'selected' : '' }}>
                                    {{ $company->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('company_id')" class="mt-2" />
                    </div>

                    <!-- Rol del Usuario -->
                    <div class="mt-4">
                        <x-input-label for="role" :value="__('Rol del Usuario')" />
                        <select id="role" name="role"
                            class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                            <!--<option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Administrador</option>-->
                            <option value="seller" {{ old('role', $user->role) == 'seller' ? 'selected' : '' }}>Vendedor / Encargado</option>
                            <option value="cashier" {{ old('role', $user->role) == 'cashier' ? 'selected' : '' }}>Cajero</option>
                        </select>
                        <x-input-error :messages="$errors->get('role')" class="mt-2" />
                    </div>

                    <div class="mt-4">
                        <x-input-label for="status" :value="__('Estatus')" />
                        <select id="status" name="status"
                            class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                            required>
                            <option value="active" {{ old('status', $user->status ?? 'active') == 'active' ? 'selected' : '' }}>Activo</option>
                            <option value="inactive" {{ old('status', $user->status ?? '') == 'inactive' ? 'selected' : '' }}>Inactivo</option>
                        </select>
                        <x-input-error :messages="$errors->get('status')" class="mt-2" />
                    </div>

                    <!-- Password (Opcional) -->
                    <div class="mt-4">
                        <x-input-label for="password" :value="__('Nueva Contraseña (Opcional)')" />
                        <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Confirm Password -->
                    <div class="mt-4">
                        <x-input-label for="password_confirmation" :value="__('Confirmar Nueva Contraseña')" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                            name="password_confirmation" />
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <a href="{{ route('usuarios.index') }}"
                            class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 underline">
                            {{ __('Cancelar') }}
                        </a>

                        <x-primary-button>
                            {{ __('Actualizar Usuario') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>