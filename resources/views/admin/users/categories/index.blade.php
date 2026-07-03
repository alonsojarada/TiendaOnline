<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('Lista de Categorías') }}
        </h2>
    </x-slot>

    <div class="pt-4 pb-4">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div
                    class="mb-4 font-medium text-sm text-green-600 dark:text-green-400 bg-green-100 dark:bg-green-900/30 p-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">

                <div class="flex justify-end mb-4">
                    <a href="{{ route('categorias.crear') }}"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 dark:bg-indigo-500 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 dark:hover:bg-indigo-600 focus:bg-indigo-700 dark:focus:bg-indigo-600 active:bg-indigo-900 dark:active:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4">
                            </path>
                        </svg>
                        {{ __('Nueva Categoría') }}
                    </a>
                </div>


                @if($categories->isEmpty())
                    <p class="text-gray-500 dark:text-gray-400 text-center py-4">No hay categorías registradas aún.</p>
                @else
                    <div class="overflow-x-auto">
                        <table
                            class="min-w-full divide-y divide-gray-200 dark:divide-gray-700 text-sm text-left text-gray-500 dark:text-gray-400">
                            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                                <tr>
                                    <th scope="col" class="px-6 py-3">Nombre</th>
                                    <th scope="col" class="px-6 py-3">Slug (URL)</th>
                                    <th scope="col" class="px-6 py-3">Descripción</th>
                                    <th scope="col" class="px-6 py-3 relative"><span class="sr-only">Acciones</span></th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                                @foreach($categories as $category)
                                    <tr
                                        class="bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                                        <td class="px-6 py-4 font-medium text-gray-900 dark:text-white whitespace-nowrap">
                                            {{ $category->name }}
                                        </td>
                                        <td
                                            class="px-6 py-4 font-mono text-xs text-indigo-500 dark:text-indigo-400 whitespace-nowrap">
                                            {{ $category->slug }}
                                        </td>
                                        <td class="px-6 py-4 max-w-xs truncate">
                                            {{ $category->description ?? 'Sin descripción' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <a href="{{ route('categorias.edit', $category) }}"
                                                class="text-indigo-600 dark:text-indigo-400 hover:text-indigo-900 dark:hover:text-indigo-300 focus:outline-none focus:underline">
                                                {{ __('Editar') }}
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>