<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight ">
            {{ __('Lista de Usuarios') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-md rounded-lg overflow-hidden">
                <table class="min-w-full leading-normal">
                    <thead>
                        <tr class="bg-gray-100 text-gray-600 text-left text-sm uppercase font-semibold">
                            <th class="px-5 py-3">ID</th>
                            <th class="px-5 py-3">Nombre</th>
                            <th class="px-5 py-3">Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($usuarios as $usuario)
                            <tr class="border-b border-gray-200 text-sm">
                                <td class="px-5 py-4">{{ $usuario->id }}</td>
                                <td class="px-5 py-4">{{ $usuario->nombre }}</td>
                                <td class="px-5 py-4">{{ $usuario->email }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>