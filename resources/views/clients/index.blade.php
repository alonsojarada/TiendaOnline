<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Directorio de Clientes
        </h2>
        <p class="text-sm text-gray-500 dark:text-gray-400">
            Gestión de clientes, información de contacto y cuentas asociadas.
        </p>
    </x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <!-- CONTENEDOR PRINCIPAL ESTILO TARJETA -->
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl shadow-xs border border-gray-200 dark:border-gray-700 overflow-hidden">

            <!-- BARRA DE BÚSQUEDA, EXPORTACIÓN Y BOTÓN NUEVO CLIENTE -->
            <div
                class="p-5 sm:p-6 border-b border-gray-200 dark:border-gray-700 flex flex-col lg:flex-row items-center justify-between gap-4">
                <div class="relative w-full lg:w-96">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3.5 text-gray-400">🔍</span>
                    <input type="text" id="buscarCliente" placeholder="Buscar por nombre, alias o dirección..."
                        class="w-full pl-10 pr-4 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-sm focus:ring-2 focus:ring-indigo-500 focus:outline-hidden text-gray-800 dark:text-gray-200">
                </div>

                <div class="flex flex-wrap items-center gap-2.5 w-full lg:w-auto justify-end">
                    <!-- BOTÓN EXCEL -->
                    <a href="{{ route('clients.export.excel') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-sm font-semibold shadow-xs transition">
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                        </svg>
                        Excel
                    </a>

                    <!-- BOTÓN PDF -->
                    <a href="{{ route('clients.export.pdf') }}"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-rose-600 hover:bg-rose-700 text-white rounded-lg text-sm font-semibold shadow-xs transition">
                        <svg style="width: 16px; height: 16px;" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        Descargar PDF
                    </a>

                    <!-- BOTÓN NUEVO CLIENTE -->
                    <button type="button"
                        onclick="document.getElementById('modalNuevoCliente').classList.remove('hidden')"
                        class="inline-flex items-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg text-sm font-semibold shadow-xs transition">
                        <span>+</span> Nuevo Cliente
                    </button>
                </div>
            </div>

            <!-- TABLA DE CLIENTES -->
            <!-- TABLA DE CLIENTES CON SCROLL INDEPENDIENTE -->
            <div class="max-h-[65vh] overflow-y-auto overflow-x-auto">
                <table class="w-full text-left border-collapse relative">
                    <thead class="sticky top-0 z-10">
                        <tr
                            class="bg-gray-100 dark:bg-gray-900 text-xs font-black text-gray-700 dark:text-gray-300 uppercase tracking-wider border-b-2 border-gray-200 dark:border-gray-700">
                            <th class="py-2.5 px-5 border-r border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900">Cliente</th>
                            <th class="py-2.5 px-5 border-r border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900">Alias</th>
                            <th class="py-2.5 px-5 border-r border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900">Dirección</th>
                            <th class="py-2.5 px-5 border-r border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900">Teléfono</th>
                            <th class="py-2.5 px-5 border-r border-gray-200 dark:border-gray-700 bg-gray-100 dark:bg-gray-900">Estatus</th>
                            <th class="py-2.5 px-5 text-right bg-gray-100 dark:bg-gray-900">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 text-sm">
                        @forelse($clients as $client)
                            <tr class="hover:bg-gray-50/75 dark:hover:bg-gray-700/50 transition fila-cliente">
                                <!-- Columna Cliente -->
                                <td class="py-1.5 px-5 border-r border-gray-100 dark:border-gray-700/50">
                                    <div class="flex items-center gap-3">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-indigo-100 dark:bg-indigo-950/80 text-indigo-700 dark:text-indigo-300 font-black flex items-center justify-center text-xs shrink-0 shadow-xs">
                                            {{ strtoupper(substr($client->name, 0, 2)) }}
                                        </div>
                                        <div class="font-bold text-gray-900 dark:text-white text-sm">{{ $client->name }}
                                        </div>
                                    </div>
                                </td>

                                <!-- Columna Alias -->
                                <td
                                    class="py-1.5 px-5 border-r border-gray-100 dark:border-gray-700/50 text-gray-600 dark:text-gray-400 font-medium text-sm">
                                    {{ $client->alias ? '"' . $client->alias . '"' : '—' }}
                                </td>

                                <!-- Columna Dirección -->
                                <td
                                    class="py-1.5 px-5 border-r border-gray-100 dark:border-gray-700/50 text-gray-800 dark:text-gray-200 font-medium text-sm">
                                    {{ $client->address ?? 'Sin dirección' }}
                                </td>

                                <!-- Columna Teléfono -->
                                <td
                                    class="py-1.5 px-5 border-r border-gray-100 dark:border-gray-700/50 text-gray-700 dark:text-gray-300 font-mono font-semibold text-sm">
                                    {{ $client->phone ?? 'Sin teléfono' }}
                                </td>

                                <!-- Columna Estatus -->
                                <td class="py-1.5 px-5 border-r border-gray-100 dark:border-gray-700/50">
                                    @if($client->status === 'active')
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900/40 dark:text-green-300">
                                            Activo
                                        </span>
                                    @else
                                        <span class="px-2.5 py-1 inline-flex text-xs leading-4 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900/40 dark:text-red-300">
                                            Suspendido
                                        </span>
                                    @endif
                                </td>

                                <!-- Columna Acciones -->
                                <td class="py-1.5 px-5 text-right">
                                    <div class="inline-flex items-center justify-end gap-1.5">
                                        <button type="button"
                                            onclick="abrirModalEditar('{{ $client->id }}', '{{ addslashes($client->name) }}', '{{ addslashes($client->alias) }}', '{{ $client->phone }}', '{{ addslashes($client->address ?? '') }}', '{{ $client->status ?? 'activo' }}')"
                                            class="px-3 py-1 bg-amber-100 hover:bg-amber-200 text-amber-800 dark:bg-amber-900/40 dark:text-amber-300 rounded-lg text-xs font-bold transition shadow-xs">
                                            Editar
                                        </button>

                                        <a href="{{ route('clients.show', $client->id) }}"
                                            class="px-3 py-1 bg-indigo-100 hover:bg-indigo-200 text-indigo-800 dark:bg-indigo-900/40 dark:text-indigo-300 rounded-lg text-xs font-bold transition shadow-xs">
                                            Cuenta ➔
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-6 text-gray-500 dark:text-gray-400 text-sm">No hay
                                    clientes registrados.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- MODAL DE NUEVO CLIENTE -->
    <div id="modalNuevoCliente"
        class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-200 dark:border-gray-700">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Registrar Nuevo Cliente</h3>

            <form action="{{ route('clients.store') }}" method="POST">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label
                            class="block text-xs font-bold uppercase text-gray-500 dark:text-gray-400 mb-1">Nombre</label>
                        <input type="text" name="name" required placeholder="Ej. Juan Pérez"
                            class="w-full text-sm px-3.5 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-800 dark:text-gray-200 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 dark:text-gray-400 mb-1">Alias
                            (Apodo)</label>
                        <input type="text" name="alias" placeholder="Ej. El Chuy"
                            class="w-full text-sm px-3.5 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-800 dark:text-gray-200 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold uppercase text-gray-500 dark:text-gray-400 mb-1">Teléfono</label>
                        <input type="text" name="phone" placeholder="Ej. 8714663905"
                            class="w-full text-sm px-3.5 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-800 dark:text-gray-200 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold uppercase text-gray-500 dark:text-gray-400 mb-1">Dirección</label>
                        <input type="text" name="address" placeholder="Ej. Boquillas #123"
                            class="w-full text-sm px-3.5 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-800 dark:text-gray-200 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold uppercase text-gray-500 dark:text-gray-400 mb-1">Estatus</label>
                        <select name="status"
                            class="w-full text-sm px-3.5 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-800 dark:text-gray-200 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                            <option value="active">Activo</option>
                            <option value="inactive">Suspendido</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button type="button" onclick="document.getElementById('modalNuevoCliente').classList.add('hidden')"
                        class="px-4 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-bold uppercase transition">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold uppercase transition shadow-xs">
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL DE EDITAR CLIENTE -->
    <div id="modalEditarCliente"
        class="hidden fixed inset-0 z-50 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
        <div
            class="bg-white dark:bg-gray-800 rounded-2xl max-w-md w-full p-6 shadow-xl border border-gray-200 dark:border-gray-700">
            <h3 class="text-base font-bold text-gray-900 dark:text-white mb-4">Editar Información del Cliente</h3>

            <form id="formEditarCliente" method="POST">
                @csrf
                @method('PUT')

                <div class="space-y-4">
                    <div>
                        <label
                            class="block text-xs font-bold uppercase text-gray-500 dark:text-gray-400 mb-1">Nombre</label>
                        <input type="text" name="name" id="edit_name" required
                            class="w-full text-sm px-3.5 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-800 dark:text-gray-200 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-gray-500 dark:text-gray-400 mb-1">Alias
                            (Apodo)</label>
                        <input type="text" name="alias" id="edit_alias"
                            class="w-full text-sm px-3.5 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-800 dark:text-gray-200 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold uppercase text-gray-500 dark:text-gray-400 mb-1">Teléfono</label>
                        <input type="text" name="phone" id="edit_phone"
                            class="w-full text-sm px-3.5 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-800 dark:text-gray-200 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold uppercase text-gray-500 dark:text-gray-400 mb-1">Dirección</label>
                        <input type="text" name="address" id="edit_address"
                            class="w-full text-sm px-3.5 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-800 dark:text-gray-200 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                    </div>

                    <div>
                        <label
                            class="block text-xs font-bold uppercase text-gray-500 dark:text-gray-400 mb-1">Estatus</label>
                        <select name="status" id="edit_status"
                            class="w-full text-sm px-3.5 py-2.5 bg-gray-50 dark:bg-gray-900 border border-gray-300 dark:border-gray-700 rounded-xl text-gray-800 dark:text-gray-200 focus:outline-hidden focus:ring-2 focus:ring-indigo-500">
                            <option value="active">Activo</option>
                            <option value="inactive">Suspendido</option>
                        </select>
                    </div>
                </div>

                <div class="flex justify-end gap-2 mt-6">
                    <button type="button"
                        onclick="document.getElementById('modalEditarCliente').classList.add('hidden')"
                        class="px-4 py-2.5 bg-gray-100 dark:bg-gray-700 hover:bg-gray-200 text-gray-700 dark:text-gray-300 rounded-xl text-xs font-bold uppercase transition">
                        Cancelar
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-bold uppercase transition shadow-xs">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function abrirModalEditar(id, name, alias, phone, address, status) {
            const form = document.getElementById('formEditarCliente');
            form.action = `/clients/${id}`;

            document.getElementById('edit_name').value = name;
            document.getElementById('edit_alias').value = alias !== 'null' ? alias : '';
            document.getElementById('edit_phone').value = phone !== 'null' ? phone : '';
            document.getElementById('edit_address').value = address !== 'null' ? address : '';
            document.getElementById('edit_status').value = status;

            document.getElementById('modalEditarCliente').classList.remove('hidden');
        }

        // Buscador en vivo
        document.getElementById('buscarCliente').addEventListener('input', function (e) {
            const texto = e.target.value.toLowerCase().trim();
            const filas = document.querySelectorAll('.fila-cliente');

            filas.forEach(fila => {
                const contenidoFila = fila.innerText.toLowerCase();
                if (contenidoFila.includes(texto)) {
                    fila.style.display = '';
                } else {
                    fila.style.display = 'none';
                }
            });
        });
    </script>
</x-app-layout>