<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('Registrar Nueva Empresa') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                <form method="POST" action="{{ route('companies.store') }}" class="max-w-md mx-auto space-y-4">
                    @csrf

                    <!-- Nombre -->
                    <div>
                        <x-input-label for="name" :value="__('Nombre de la Empresa')" />
                        <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <!-- Slug -->
                    <div class="mt-4">
                        <x-input-label for="slug" :value="__('Slug (Identificador único ej. mi-tienda)')" />
                        <x-text-input id="slug" class="block mt-1 w-full" type="text" name="slug" :value="old('slug')" required />
                        <x-input-error :messages="$errors->get('slug')" class="mt-2" />
                    </div>

                    <!-- Teléfono -->
                    <div class="mt-4">
                        <x-input-label for="phone" :value="__('Teléfono (Opcional)')" />
                        <x-text-input id="phone" class="block mt-1 w-full" type="text" name="phone" :value="old('phone')" />
                        <x-input-error :messages="$errors->get('phone')" class="mt-2" />
                    </div>

                    <!-- Dirección -->
                    <div class="mt-4">
                        <x-input-label for="address" :value="__('Dirección (Opcional)')" />
                        <x-text-input id="address" class="block mt-1 w-full" type="text" name="address" :value="old('address')" />
                        <x-input-error :messages="$errors->get('address')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-between mt-4">
                        <a href="{{ route('companies.index') }}" class="text-sm text-gray-600 dark:text-gray-400 underline">
                            {{ __('Cancelar') }}
                        </a>

                        <x-primary-button>
                            {{ __('Guardar Empresa') }}
                        </x-primary-button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>