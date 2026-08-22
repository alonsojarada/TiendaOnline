<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Lista de Usuarios') }}
            </h2>
            <a href="{{ route('usuarios.crear') }}"
                class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700 text-white text-xs font-semibold uppercase tracking-widest rounded-xl shadow-sm hover:shadow transition">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                </svg>
                Nuevo Usuario
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-6 bg-emerald-50 dark:bg-emerald-950/50 border border-emerald-200 dark:border-emerald-800 text-emerald-700 dark:text-emerald-300 px-4 py-3 rounded-xl shadow-sm flex items-center">
                    <svg class="w-5 h-5 mr-2 text-emerald-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    {{ session('status') }}
                </div>
            @endif

            <div class="bg-white dark:bg-gray-900 border border-gray-100 dark:border-gray-800 shadow-xl rounded-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-800">
                        <thead class="bg-gray-50/75 dark:bg-gray-800/50 text-gray-500 dark:text-gray-400 text-left text-xs uppercase font-bold tracking-wider">
                            <tr>
                                <th class="px-6 py-4">ID</th>
                                <th class="px-6 py-4">Nombre</th>
                                <th class="px-6 py-4">Email</th>
                                <th class="px-6 py-4">Empresa Asignada</th>
                                <th class="px-6 py-4">Rol</th>
                                <th class="px-6 py-4">Estatus</th>
                                <th class="px-6 py-4 text-center">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800 bg-white dark:bg-gray-900 text-gray-800 dark:text-gray-200">
                            @forelse ($usuarios as $usuario)
                                <tr class="hover:bg-gray-50/50 dark:hover:bg-gray-800/40 transition-colors text-sm">
                                    <td class="px-6 py-4 whitespace-nowrap font-semibold text-gray-900 dark:text-gray-100">
                                        #{{ $usuario->id }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium">
                                        {{ $usuario->name }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500 dark:text-gray-400">
                                        {{ $usuario->email }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600 dark:text-gray-300">
                                        {{ $usuario->company->name ?? 'Sin empresa' }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-lg 
                                            {{ $usuario->role === 'admin' ? 'bg-purple-50 text-purple-700 dark:bg-purple-950/50 dark:text-purple-300 border border-purple-200 dark:border-purple-800' : 'bg-blue-50 text-blue-700 dark:bg-blue-950/50 dark:text-blue-300 border border-blue-200 dark:border-blue-800' }}">
                                            {{ ucfirst($usuario->role) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-1 text-xs font-semibold rounded-lg 
                                            {{ $usuario->status === 'active' ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800' : 'bg-red-50 text-red-700 dark:bg-red-950/50 dark:text-red-300 border border-red-200 dark:border-red-800' }}">
                                            @if($usuario->status === 'active')
                                                <span class="w-1.5 h-1.5 mr-1.5 bg-emerald-500 rounded-full animate-pulse"></span>
                                            @endif
                                            {{ $usuario->status === 'active' ? 'Activo' : 'Inactivo' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <a href="{{ route('usuarios.edit', $usuario->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 bg-gray-100 dark:bg-gray-800 hover:bg-indigo-50 dark:hover:bg-indigo-950/50 text-gray-700 dark:text-gray-300 hover:text-indigo-600 dark:hover:text-indigo-400 text-xs font-semibold rounded-lg transition border border-gray-200 dark:border-gray-700">
                                            Editar
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-12 text-center text-sm text-gray-500 dark:text-gray-400">
                                        No hay usuarios registrados en el sistema.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>