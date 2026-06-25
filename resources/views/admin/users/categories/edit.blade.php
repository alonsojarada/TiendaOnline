<x-app-layout>
    {{-- 1. Cabecera de la página (Estilo Breeze) --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Editar Categoría') }}
        </h2>
    </x-slot>

    {{-- 2. Contenedor principal con márgenes y fondo blanco --}}
    <div class="py-12">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                
                {{-- 3. Formulario que apunta a la ruta de actualización --}}
                <form method="POST" action="{{ route('categorias.update', $category) }}" class="space-y-6">
                    {{-- Token de seguridad obligatorio en Laravel --}}
                    @csrf
                    
                    {{-- Directiva obligatoria para indicarle a Laravel que simule un método PUT (actualización) --}}
                    @method('PUT')

                    <div>
                        <x-input-label for="name" :value="__('Nombre de la Categoría')" />
                        {{-- :value="old(...)" mantiene lo que el usuario escribió si hay un error de validación, de lo contrario muestra el nombre actual --}}
                        <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" :value="old('name', $category->name)" required autofocus />
                        {{-- Muestra errores de validación específicos para este campo --}}
                        <x-input-error class="mt-2" :messages="$errors->get('name')" />
                    </div>

                    <div>
                        <x-input-label for="description" :value="__('Descripción')" />
                        {{-- Usamos un textarea estilizado con Tailwind idéntico a los inputs de Breeze --}}
                        <textarea id="description" name="description" class="mt-1 block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" rows="4">{{ old('description', $category->description) }}</textarea>
                        <x-input-error class="mt-2" :messages="$errors->get('description')" />
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>{{ __('Guardar Cambios') }}</x-primary-button>

                        <a href="{{ route('categorias.index') }}" class="text-sm text-gray-600 hover:text-gray-900 underline rounded-md">
                            {{ __('Cancelar') }}
                        </a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</x-app-layout>