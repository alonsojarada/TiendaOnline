<x-app-layout>

    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-2 sm:px-6 lg:px-8">

            <h2 class="font-semibold text-base sm:text-xl
                       text-gray-800 dark:text-gray-200
                       leading-tight">
                Directorio de Clientes
            </h2>

            <p class="text-[11px] sm:text-sm
                      text-gray-500 dark:text-gray-400">
                Gestión de clientes, información de contacto y cuentas asociadas.
            </p>

        </div>
    </x-slot>


    <div class="max-w-7xl mx-auto
                px-2 sm:px-6 lg:px-8
                py-3 sm:py-6">

        <!-- =========================================================
             CONTENEDOR PRINCIPAL
        ========================================================== -->

        <div id="contenedorDirectorio"
            class="bg-white dark:bg-gray-800
                   rounded-xl sm:rounded-2xl
                   shadow-xs
                   border border-gray-200 dark:border-gray-700
                   overflow-hidden">


            <!-- =====================================================
                 BARRA DE ACCIONES
            ====================================================== -->

            <div id="barraAccionesClientes"
                class="p-2.5 sm:p-5 lg:p-6
                       border-b border-gray-200 dark:border-gray-700
                       w-full">

                <div id="contenidoAccionesClientes"
                    class="w-full">


                    <!-- =================================================
                         BUSCADOR
                    ================================================== -->

                    <div id="bloqueBusquedaClientes"
                        class="relative min-w-0">

                        <span class="absolute inset-y-0 left-0
                                     flex items-center
                                     pl-3
                                     pointer-events-none
                                     text-gray-400
                                     text-xs">
                            🔍
                        </span>

                        <input type="text"
                            id="buscarCliente"
                            placeholder="Buscar por nombre, alias o dirección..."
                            class="w-full
                                   pl-9 pr-3
                                   py-2
                                   bg-gray-50 dark:bg-gray-900
                                   border border-gray-300 dark:border-gray-700
                                   rounded-xl
                                   text-xs sm:text-sm
                                   focus:ring-2 focus:ring-indigo-500
                                   focus:outline-hidden
                                   text-gray-800 dark:text-gray-200">

                    </div>


                    <!-- =================================================
                         BOTONES
                    ================================================== -->

                    <div id="botonesClientes"
                        class="flex items-center
                               gap-2
                               shrink-0">


                        <!-- =================================================
                             EXCEL
                        ================================================== -->

                        <a href="{{ route('clients.export.excel') }}"
                            title="Exportar Excel"
                            class="boton-cliente
                                   inline-flex items-center justify-center
                                   gap-2
                                   px-3 py-2
                                   bg-emerald-600 hover:bg-emerald-700
                                   text-white
                                   rounded-lg
                                   text-xs sm:text-sm
                                   font-semibold
                                   shadow-xs
                                   transition
                                   whitespace-nowrap">

                            <svg class="icono-cliente"
                                style="width:16px;height:16px;"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />

                            </svg>

                            <span class="texto-boton-cliente">
                                Excel
                            </span>

                        </a>


                        <!-- =================================================
                             PDF
                        ================================================== -->

                        <a href="{{ route('clients.export.pdf') }}"
                            title="Descargar PDF"
                            class="boton-cliente
                                   inline-flex items-center justify-center
                                   gap-2
                                   px-3 py-2
                                   bg-rose-600 hover:bg-rose-700
                                   text-white
                                   rounded-lg
                                   text-xs sm:text-sm
                                   font-semibold
                                   shadow-xs
                                   transition
                                   whitespace-nowrap">

                            <svg class="icono-cliente"
                                style="width:16px;height:16px;"
                                fill="none"
                                stroke="currentColor"
                                stroke-width="2"
                                viewBox="0 0 24 24">

                                <path stroke-linecap="round"
                                    stroke-linejoin="round"
                                    d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />

                            </svg>

                            <span class="texto-boton-cliente">

                                <span class="texto-pdf-completo">
                                    Descargar PDF
                                </span>

                                <span class="texto-pdf-corto">
                                    PDF
                                </span>

                            </span>

                        </a>


                        <!-- =================================================
                             NUEVO CLIENTE
                        ================================================== -->

                        <button type="button"
                            onclick="document.getElementById('modalNuevoCliente').classList.remove('hidden')"
                            title="Nuevo Cliente"
                            class="boton-cliente
                                   inline-flex items-center justify-center
                                   gap-2
                                   px-3 py-2
                                   bg-indigo-600 hover:bg-indigo-700
                                   text-white
                                   rounded-lg
                                   text-xs sm:text-sm
                                   font-semibold
                                   shadow-xs
                                   transition
                                   whitespace-nowrap">

                            <span class="text-base leading-none">
                                +
                            </span>

                            <span class="texto-boton-cliente">

                                <span class="texto-nuevo-completo">
                                    Nuevo Cliente
                                </span>

                                <span class="texto-nuevo-corto">
                                    Cliente
                                </span>

                            </span>

                        </button>

                    </div>

                </div>

            </div>


            <!-- =========================================================
                 TABLA
            ========================================================== -->

            <div id="scrollTablaClientes"
                class="overflow-y-auto overflow-x-hidden
                       relative rounded-b-2xl">

                <table id="tablaClientes"
                    class="w-full text-left border-collapse tabla-clientes">

                    <!-- =================================================
                         ENCABEZADO
                    ================================================== -->

                    <thead class="sticky top-0 z-20 shadow-xs">

                        <tr class="bg-gray-100 dark:bg-gray-900
                                   text-[10px] sm:text-xs
                                   font-black
                                   text-gray-700 dark:text-gray-300
                                   uppercase
                                   tracking-wider
                                   border-b-2
                                   border-gray-200 dark:border-gray-700">

                            <th class="py-2 sm:py-2.5
                                       px-2 sm:px-5
                                       border-r
                                       border-gray-200 dark:border-gray-700
                                       bg-gray-100 dark:bg-gray-900">
                                Cliente
                            </th>

                            <th class="py-2.5 px-5
                                       border-r
                                       border-gray-200 dark:border-gray-700
                                       bg-gray-100 dark:bg-gray-900
                                       hidden sm:table-cell">
                                Alias
                            </th>

                            <th class="py-2 sm:py-2.5
                                       px-2 sm:px-5
                                       border-r
                                       border-gray-200 dark:border-gray-700
                                       bg-gray-100 dark:bg-gray-900
                                       hidden md:table-cell">
                                Dirección
                            </th>

                            <th class="py-2 sm:py-2.5
                                       px-2 sm:px-5
                                       border-r
                                       border-gray-200 dark:border-gray-700
                                       bg-gray-100 dark:bg-gray-900">
                                Teléfono
                            </th>

                            <th class="py-2 sm:py-2.5
                                       px-2 sm:px-5
                                       border-r
                                       border-gray-200 dark:border-gray-700
                                       bg-gray-100 dark:bg-gray-900">
                                Estatus
                            </th>

                            <th class="py-2 sm:py-2.5
                                       px-2 sm:px-5
                                       text-right
                                       bg-gray-100 dark:bg-gray-900">
                                Acciones
                            </th>

                        </tr>

                    </thead>


                    <!-- =================================================
                         CUERPO
                    ================================================== -->

                    <tbody class="divide-y
                                  divide-gray-200 dark:divide-gray-700
                                  text-xs sm:text-sm
                                  bg-white dark:bg-gray-800">

                        @forelse($clients as $client)

                            <tr class="hover:bg-gray-50/75
                                       dark:hover:bg-gray-700/50
                                       transition
                                       fila-cliente">

                                <!-- =====================================
                                     CLIENTE
                                ====================================== -->

                                <td class="py-1.5 sm:py-1.5
                                           px-2 sm:px-5
                                           border-r
                                           border-gray-100
                                           dark:border-gray-700/50">

                                    <div class="flex items-center">

                                        <div class="font-bold
                                                    text-gray-900
                                                    dark:text-white
                                                    text-[11px] sm:text-sm
                                                    leading-tight
                                                    truncate
                                                    min-w-0">

                                            {{ $client->name }}

                                        </div>

                                    </div>

                                </td>


                                <!-- =====================================
                                     ALIAS
                                ====================================== -->

                                <td class="py-1.5 px-5
                                           border-r
                                           border-gray-100
                                           dark:border-gray-700/50
                                           text-gray-600
                                           dark:text-gray-400
                                           font-medium
                                           text-sm
                                           hidden sm:table-cell">

                                    {{ $client->alias ? '"' . $client->alias . '"' : '—' }}

                                </td>


                                <!-- =====================================
                                     DIRECCIÓN
                                ====================================== -->

                                <td class="py-1.5 px-5
                                           border-r
                                           border-gray-100
                                           dark:border-gray-700/50
                                           text-gray-800
                                           dark:text-gray-200
                                           font-medium
                                           text-sm
                                           hidden md:table-cell">

                                    {{ $client->address ?? 'Sin dirección' }}

                                </td>


                                <!-- =====================================
                                     TELÉFONO
                                ====================================== -->

                                <td class="py-1.5
                                           px-2 sm:px-5
                                           border-r
                                           border-gray-100
                                           dark:border-gray-700/50
                                           text-gray-700
                                           dark:text-gray-300
                                           font-mono
                                           font-semibold
                                           text-[10px] sm:text-sm
                                           whitespace-nowrap">

                                    {{ $client->phone ?? 'Sin teléfono' }}

                                </td>


                                <!-- =====================================
                                     ESTATUS
                                ====================================== -->

                                <td class="py-1.5
                                           px-2 sm:px-5
                                           border-r
                                           border-gray-100
                                           dark:border-gray-700/50
                                           whitespace-nowrap">

                                    @if($client->status === 'active')

                                        <span class="px-2 sm:px-2.5
                                                     py-0.5 sm:py-1
                                                     inline-flex
                                                     text-[9px] sm:text-xs
                                                     leading-4
                                                     font-semibold
                                                     rounded-full
                                                     bg-green-100
                                                     text-green-800
                                                     dark:bg-green-900/40
                                                     dark:text-green-300">

                                            Activo

                                        </span>

                                    @else

                                        <span class="px-2 sm:px-2.5
                                                     py-0.5 sm:py-1
                                                     inline-flex
                                                     text-[9px] sm:text-xs
                                                     leading-4
                                                     font-semibold
                                                     rounded-full
                                                     bg-red-100
                                                     text-red-800
                                                     dark:bg-red-900/40
                                                     dark:text-red-300">

                                            Suspendido

                                        </span>

                                    @endif

                                </td>


                                <!-- =====================================
                                     ACCIONES
                                ====================================== -->

                                <td class="py-1.5
                                           px-2 sm:px-5
                                           text-right
                                           whitespace-nowrap">

                                    <div class="inline-flex
                                                items-center
                                                justify-end
                                                gap-1 sm:gap-1.5">

                                        <button type="button"
                                            onclick="abrirModalEditar('{{ $client->id }}', '{{ addslashes($client->name) }}', '{{ addslashes($client->alias) }}', '{{ $client->phone }}', '{{ addslashes($client->address ?? '') }}', '{{ $client->status ?? 'active' }}')"
                                            class="boton-tabla
                                                   px-2 sm:px-3
                                                   py-1
                                                   bg-amber-100
                                                   hover:bg-amber-200
                                                   text-amber-800
                                                   dark:bg-amber-900/40
                                                   dark:text-amber-300
                                                   rounded-lg
                                                   text-[9px] sm:text-xs
                                                   font-bold
                                                   transition
                                                   shadow-xs">

                                            Editar

                                        </button>


                                        <a href="{{ route('clients.show', $client->id) }}"
                                            class="boton-tabla
                                                   px-2 sm:px-3
                                                   py-1
                                                   bg-indigo-100
                                                   hover:bg-indigo-200
                                                   text-indigo-800
                                                   dark:bg-indigo-900/40
                                                   dark:text-indigo-300
                                                   rounded-lg
                                                   text-[9px] sm:text-xs
                                                   font-bold
                                                   transition
                                                   shadow-xs">

                                            <span class="hidden sm:inline">
                                                Cuenta ➔
                                            </span>

                                            <span class="sm:hidden">
                                                Cuenta
                                            </span>

                                        </a>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="6"
                                    class="text-center
                                           py-10 sm:py-12
                                           text-gray-500
                                           dark:text-gray-400
                                           text-xs sm:text-sm">

                                    No hay clientes registrados.

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    <!-- =============================================================
         MODAL NUEVO CLIENTE
    ============================================================== -->

    <div id="modalNuevoCliente"
        class="hidden fixed inset-0 z-50
               bg-black/50 backdrop-blur-sm
               flex items-center justify-center
               p-3 sm:p-4">

        <div class="bg-white dark:bg-gray-800
                    rounded-2xl
                    max-w-md
                    w-full
                    p-4 sm:p-6
                    shadow-xl
                    border border-gray-200 dark:border-gray-700">

            <h3 class="text-base font-bold
                       text-gray-900 dark:text-white
                       mb-4">

                Registrar Nuevo Cliente

            </h3>


            <form action="{{ route('clients.store') }}" method="POST">

                @csrf

                <div class="space-y-4">

                    <div>

                        <label class="block text-xs
                                      font-bold uppercase
                                      text-gray-500 dark:text-gray-400
                                      mb-1">

                            Nombre

                        </label>

                        <input type="text"
                            name="name"
                            required
                            placeholder="Ej. Juan Pérez"
                            class="w-full
                                   text-sm
                                   px-3.5 py-2.5
                                   bg-gray-50 dark:bg-gray-900
                                   border border-gray-300 dark:border-gray-700
                                   rounded-xl
                                   text-gray-800 dark:text-gray-200
                                   focus:outline-hidden
                                   focus:ring-2 focus:ring-indigo-500">

                    </div>


                    <div>

                        <label class="block text-xs
                                      font-bold uppercase
                                      text-gray-500 dark:text-gray-400
                                      mb-1">

                            Alias (Apodo)

                        </label>

                        <input type="text"
                            name="alias"
                            placeholder="Ej. El Chuy"
                            class="w-full
                                   text-sm
                                   px-3.5 py-2.5
                                   bg-gray-50 dark:bg-gray-900
                                   border border-gray-300 dark:border-gray-700
                                   rounded-xl
                                   text-gray-800 dark:text-gray-200
                                   focus:outline-hidden
                                   focus:ring-2 focus:ring-indigo-500">

                    </div>


                    <div>

                        <label class="block text-xs
                                      font-bold uppercase
                                      text-gray-500 dark:text-gray-400
                                      mb-1">

                            Teléfono

                        </label>

                        <input type="text"
                            name="phone"
                            placeholder="Ej. 8714663905"
                            class="w-full
                                   text-sm
                                   px-3.5 py-2.5
                                   bg-gray-50 dark:bg-gray-900
                                   border border-gray-300 dark:border-gray-700
                                   rounded-xl
                                   text-gray-800 dark:text-gray-200
                                   focus:outline-hidden
                                   focus:ring-2 focus:ring-indigo-500">

                    </div>


                    <div>

                        <label class="block text-xs
                                      font-bold uppercase
                                      text-gray-500 dark:text-gray-400
                                      mb-1">

                            Dirección

                        </label>

                        <input type="text"
                            name="address"
                            placeholder="Ej. Boquillas #123"
                            class="w-full
                                   text-sm
                                   px-3.5 py-2.5
                                   bg-gray-50 dark:bg-gray-900
                                   border border-gray-300 dark:border-gray-700
                                   rounded-xl
                                   text-gray-800 dark:text-gray-200
                                   focus:outline-hidden
                                   focus:ring-2 focus:ring-indigo-500">

                    </div>


                    <div>

                        <label class="block text-xs
                                      font-bold uppercase
                                      text-gray-500 dark:text-gray-400
                                      mb-1">

                            Estatus

                        </label>

                        <select name="status"
                            class="w-full
                                   text-sm
                                   px-3.5 py-2.5
                                   bg-gray-50 dark:bg-gray-900
                                   border border-gray-300 dark:border-gray-700
                                   rounded-xl
                                   text-gray-800 dark:text-gray-200
                                   focus:outline-hidden
                                   focus:ring-2 focus:ring-indigo-500">

                            <option value="active">
                                Activo
                            </option>

                            <option value="inactive">
                                Suspendido
                            </option>

                        </select>

                    </div>

                </div>


                <div class="flex justify-end
                            gap-2
                            mt-6">

                    <button type="button"
                        onclick="document.getElementById('modalNuevoCliente').classList.add('hidden')"
                        class="px-4 py-2.5
                               bg-gray-100 dark:bg-gray-700
                               hover:bg-gray-200
                               text-gray-700 dark:text-gray-300
                               rounded-xl
                               text-xs
                               font-bold uppercase
                               transition">

                        Cancelar

                    </button>


                    <button type="submit"
                        class="px-5 py-2.5
                               bg-indigo-600 hover:bg-indigo-700
                               text-white
                               rounded-xl
                               text-xs
                               font-bold uppercase
                               transition
                               shadow-xs">

                        Guardar

                    </button>

                </div>

            </form>

        </div>

    </div>


    <!-- =============================================================
         MODAL EDITAR CLIENTE
    ============================================================== -->

    <div id="modalEditarCliente"
        class="hidden fixed inset-0 z-50
               bg-black/50 backdrop-blur-sm
               flex items-center justify-center
               p-3 sm:p-4">

        <div class="bg-white dark:bg-gray-800
                    rounded-2xl
                    max-w-md
                    w-full
                    p-4 sm:p-6
                    shadow-xl
                    border border-gray-200 dark:border-gray-700">

            <h3 class="text-base font-bold
                       text-gray-900 dark:text-white
                       mb-4">

                Editar Información del Cliente

            </h3>


            <form id="formEditarCliente"
                method="POST">

                @csrf
                @method('PUT')

                <div class="space-y-4">

                    <div>

                        <label class="block text-xs
                                      font-bold uppercase
                                      text-gray-500 dark:text-gray-400
                                      mb-1">

                            Nombre

                        </label>

                        <input type="text"
                            name="name"
                            id="edit_name"
                            required
                            class="w-full
                                   text-sm
                                   px-3.5 py-2.5
                                   bg-gray-50 dark:bg-gray-900
                                   border border-gray-300 dark:border-gray-700
                                   rounded-xl
                                   text-gray-800 dark:text-gray-200
                                   focus:outline-hidden
                                   focus:ring-2 focus:ring-indigo-500">

                    </div>


                    <div>

                        <label class="block text-xs
                                      font-bold uppercase
                                      text-gray-500 dark:text-gray-400
                                      mb-1">

                            Alias (Apodo)

                        </label>

                        <input type="text"
                            name="alias"
                            id="edit_alias"
                            class="w-full
                                   text-sm
                                   px-3.5 py-2.5
                                   bg-gray-50 dark:bg-gray-900
                                   border border-gray-300 dark:border-gray-700
                                   rounded-xl
                                   text-gray-800 dark:text-gray-200
                                   focus:outline-hidden
                                   focus:ring-2 focus:ring-indigo-500">

                    </div>


                    <div>

                        <label class="block text-xs
                                      font-bold uppercase
                                      text-gray-500 dark:text-gray-400
                                      mb-1">

                            Teléfono

                        </label>

                        <input type="text"
                            name="phone"
                            id="edit_phone"
                            class="w-full
                                   text-sm
                                   px-3.5 py-2.5
                                   bg-gray-50 dark:bg-gray-900
                                   border border-gray-300 dark:border-gray-700
                                   rounded-xl
                                   text-gray-800 dark:text-gray-200
                                   focus:outline-hidden
                                   focus:ring-2 focus:ring-indigo-500">

                    </div>


                    <div>

                        <label class="block text-xs
                                      font-bold uppercase
                                      text-gray-500 dark:text-gray-400
                                      mb-1">

                            Dirección

                        </label>

                        <input type="text"
                            name="address"
                            id="edit_address"
                            class="w-full
                                   text-sm
                                   px-3.5 py-2.5
                                   bg-gray-50 dark:bg-gray-900
                                   border border-gray-300 dark:border-gray-700
                                   rounded-xl
                                   text-gray-800 dark:text-gray-200
                                   focus:outline-hidden
                                   focus:ring-2 focus:ring-indigo-500">

                    </div>


                    <div>

                        <label class="block text-xs
                                      font-bold uppercase
                                      text-gray-500 dark:text-gray-400
                                      mb-1">

                            Estatus

                        </label>

                        <select name="status"
                            id="edit_status"
                            class="w-full
                                   text-sm
                                   px-3.5 py-2.5
                                   bg-gray-50 dark:bg-gray-900
                                   border border-gray-300 dark:border-gray-700
                                   rounded-xl
                                   text-gray-800 dark:text-gray-200
                                   focus:outline-hidden
                                   focus:ring-2 focus:ring-indigo-500">

                            <option value="active">
                                Activo
                            </option>

                            <option value="inactive">
                                Suspendido
                            </option>

                        </select>

                    </div>

                </div>


                <div class="flex justify-end
                            gap-2
                            mt-6">

                    <button type="button"
                        onclick="document.getElementById('modalEditarCliente').classList.add('hidden')"
                        class="px-4 py-2.5
                               bg-gray-100 dark:bg-gray-700
                               hover:bg-gray-200
                               text-gray-700 dark:text-gray-300
                               rounded-xl
                               text-xs
                               font-bold uppercase
                               transition">

                        Cancelar

                    </button>


                    <button type="submit"
                        class="px-5 py-2.5
                               bg-indigo-600 hover:bg-indigo-700
                               text-white
                               rounded-xl
                               text-xs
                               font-bold uppercase
                               transition
                               shadow-xs">

                        Guardar Cambios

                    </button>

                </div>

            </form>

        </div>

    </div>


    <!-- =============================================================
         ESTILOS RESPONSIVOS
    ============================================================== -->

    <style>

        /* ==========================================================
           BARRA PRINCIPAL
        ========================================================== */

        #contenidoAccionesClientes {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            align-items: center;

            width: 100%;

            gap: 0.75rem;

            min-width: 0;
        }


        /* ==========================================================
           BUSCADOR
        ========================================================== */

        #bloqueBusquedaClientes {
            flex: 1 1 auto;

            width: auto;

            min-width: 0;
        }

        #bloqueBusquedaClientes input {
            width: 100%;
            min-width: 0;
        }


        /* ==========================================================
           BOTONES
        ========================================================== */

        #botonesClientes {
            display: flex;
            flex-direction: row;
            flex-wrap: nowrap;
            align-items: center;
            justify-content: flex-end;

            gap: 0.45rem;

            flex: 0 1 auto;

            width: auto;

            min-width: max-content;
        }

        .boton-cliente {
            flex: 0 1 auto;
            flex-shrink: 1;

            white-space: nowrap;

            min-width: 0;
        }


        /* ==========================================================
           TEXTOS
        ========================================================== */

        .texto-pdf-corto,
        .texto-nuevo-corto {
            display: none;
        }


        /* ==========================================================
           MODO MEDIANO
        ========================================================== */

        #contenidoAccionesClientes.modo-mediano {
            gap: 0.55rem;
        }

        #contenidoAccionesClientes.modo-mediano
        #bloqueBusquedaClientes {
            flex: 1 1 0%;
            min-width: 70px;
        }

        #contenidoAccionesClientes.modo-mediano
        #bloqueBusquedaClientes input {
            font-size: 11px;

            padding-top: 0.4rem;
            padding-bottom: 0.4rem;

            padding-left: 2rem;
            padding-right: 0.5rem;

            border-radius: 0.6rem;
        }

        #contenidoAccionesClientes.modo-mediano
        #bloqueBusquedaClientes span {
            padding-left: 0.6rem;
            font-size: 10px;
        }

        #contenidoAccionesClientes.modo-mediano
        #botonesClientes {
            gap: 0.35rem;
        }

        #contenidoAccionesClientes.modo-mediano
        .boton-cliente {
            gap: 0.25rem;

            padding-left: 0.55rem;
            padding-right: 0.55rem;

            padding-top: 0.36rem;
            padding-bottom: 0.36rem;

            font-size: 9px;

            border-radius: 0.55rem;
        }

        #contenidoAccionesClientes.modo-mediano
        .icono-cliente {
            width: 0.78rem !important;
            height: 0.78rem !important;
        }


        /* ==========================================================
           MODO PEQUEÑO
        ========================================================== */

        #contenidoAccionesClientes.modo-pequeno {
            gap: 0.35rem;
        }

        #contenidoAccionesClientes.modo-pequeno
        #bloqueBusquedaClientes {
            flex: 1 1 0%;
            min-width: 40px;
        }

        #contenidoAccionesClientes.modo-pequeno
        #bloqueBusquedaClientes input {
            font-size: 10px;

            padding-top: 0.3rem;
            padding-bottom: 0.3rem;

            padding-left: 1.75rem;
            padding-right: 0.35rem;

            border-radius: 0.5rem;
        }

        #contenidoAccionesClientes.modo-pequeno
        #bloqueBusquedaClientes span {
            padding-left: 0.5rem;
            font-size: 9px;
        }

        #contenidoAccionesClientes.modo-pequeno
        #botonesClientes {
            gap: 0.25rem;
        }

        #contenidoAccionesClientes.modo-pequeno
        .boton-cliente {
            gap: 0.18rem;

            padding-left: 0.45rem;
            padding-right: 0.45rem;

            padding-top: 0.3rem;
            padding-bottom: 0.3rem;

            font-size: 8px;

            border-radius: 0.45rem;
        }

        #contenidoAccionesClientes.modo-pequeno
        .icono-cliente {
            width: 0.68rem !important;
            height: 0.68rem !important;
        }


        /* ==========================================================
           MODO MUY PEQUEÑO
        ========================================================== */

        #contenidoAccionesClientes.modo-muy-pequeno {
            gap: 0.25rem;
        }

        #contenidoAccionesClientes.modo-muy-pequeno
        #bloqueBusquedaClientes {
            flex: 1 1 0%;
            min-width: 30px;
        }

        #contenidoAccionesClientes.modo-muy-pequeno
        #bloqueBusquedaClientes input {
            font-size: 9px;

            padding-top: 0.25rem;
            padding-bottom: 0.25rem;

            padding-left: 1.5rem;
            padding-right: 0.25rem;

            border-radius: 0.4rem;
        }

        #contenidoAccionesClientes.modo-muy-pequeno
        #bloqueBusquedaClientes span {
            padding-left: 0.4rem;
            font-size: 8px;
        }

        #contenidoAccionesClientes.modo-muy-pequeno
        #botonesClientes {
            gap: 0.18rem;
        }

        #contenidoAccionesClientes.modo-muy-pequeno
        .boton-cliente {
            gap: 0.12rem;

            padding-left: 0.36rem;
            padding-right: 0.36rem;

            padding-top: 0.26rem;
            padding-bottom: 0.26rem;

            font-size: 7.5px;

            border-radius: 0.38rem;
        }

        #contenidoAccionesClientes.modo-muy-pequeno
        .icono-cliente {
            width: 0.6rem !important;
            height: 0.6rem !important;
        }


        /* ==========================================================
           CAMBIO DE TEXTOS EN MÓVIL
        ========================================================== */

        @media (max-width: 640px) {

            .texto-pdf-completo,
            .texto-nuevo-completo {
                display: none;
            }

            .texto-pdf-corto,
            .texto-nuevo-corto {
                display: inline;
            }

        }


        /* ==========================================================
           PANTALLAS MUY PEQUEÑAS
        ========================================================== */

        @media (max-width: 480px) {

            #barraAccionesClientes {
                padding: 0.5rem;
            }

            #botonesClientes {
                gap: 0.18rem;
            }

        }


        /* ==========================================================
           SCROLL EXTREMO DE LA BARRA
        ========================================================== */

        #barraAccionesClientes.scroll-acciones {
            overflow-x: auto;
            overflow-y: hidden;
        }

        #barraAccionesClientes.scroll-acciones
        #contenidoAccionesClientes {
            min-width: 300px;
        }

        #barraAccionesClientes.scroll-acciones::-webkit-scrollbar {
            height: 5px;
        }

        #barraAccionesClientes.scroll-acciones::-webkit-scrollbar-thumb {
            background: rgba(107, 114, 128, 0.45);
            border-radius: 10px;
        }

        #barraAccionesClientes.scroll-acciones::-webkit-scrollbar-track {
            background: transparent;
        }


        /* ==========================================================
           TABLA
        ========================================================== */

        #scrollTablaClientes {
            max-height: 64vh;

            overflow-y: auto;
            overflow-x: hidden;

            scrollbar-width: thin;
        }

        #scrollTablaClientes thead {
            position: sticky;
            top: 0;
            z-index: 20;
        }

        #scrollTablaClientes::-webkit-scrollbar {
            width: 7px;
            height: 7px;
        }

        #scrollTablaClientes::-webkit-scrollbar-thumb {
            background: rgba(107, 114, 128, 0.45);
            border-radius: 10px;
        }

        #scrollTablaClientes::-webkit-scrollbar-track {
            background: transparent;
        }


        /* ==========================================================
           AJUSTE RESPONSIVO REAL DE LA TABLA
        ========================================================== */

        #tablaClientes {
            width: 100%;
            table-layout: auto;
        }


        /* ==========================================================
           PRIMER NIVEL DE COMPACTACIÓN
        ========================================================== */

        #tablaClientes.tabla-compacta th,
        #tablaClientes.tabla-compacta td {

            padding-left: 0.4rem;
            padding-right: 0.4rem;

        }

        #tablaClientes.tabla-compacta tbody {
            font-size: 0.72rem;
        }

        #tablaClientes.tabla-compacta td {
            font-size: 0.72rem;
        }


        /* ==========================================================
           SEGUNDO NIVEL DE COMPACTACIÓN
        ========================================================== */

        #tablaClientes.tabla-muy-compacta th,
        #tablaClientes.tabla-muy-compacta td {

            padding-left: 0.3rem;
            padding-right: 0.3rem;

        }

        #tablaClientes.tabla-muy-compacta tbody {
            font-size: 0.65rem;
        }

        #tablaClientes.tabla-muy-compacta td {
            font-size: 0.65rem;
        }

        #tablaClientes.tabla-muy-compacta .boton-tabla {

            padding-left: 0.4rem;
            padding-right: 0.4rem;

            font-size: 0.6rem;

        }


        /* ==========================================================
           VENTANAS MEDIANAS
           
           ALIAS SIEMPRE OCULTO
        ========================================================== */

        @media (min-width: 641px) and (max-width: 1023px) {

            #tablaClientes th:nth-child(2),
            #tablaClientes td:nth-child(2) {

                display: none;

            }

        }


        /* ==========================================================
           VENTANAS MUY PEQUEÑAS
           
           ÚNICO LUGAR DONDE SE PERMITE
           SCROLL HORIZONTAL DE LA TABLA
        ========================================================== */

        @media (max-width: 480px) {

            #scrollTablaClientes {
                overflow-x: auto;
            }

        }

    </style>


    <!-- =============================================================
         JAVASCRIPT
    ============================================================== -->

    <script>

        /* =========================================================
           MODAL EDITAR
        ========================================================== */

        function abrirModalEditar(
            id,
            name,
            alias,
            phone,
            address,
            status
        ) {

            const form =
                document.getElementById(
                    'formEditarCliente'
                );

            form.action =
                `/clients/${id}`;

            document.getElementById(
                'edit_name'
            ).value = name;

            document.getElementById(
                'edit_alias'
            ).value =
                alias !== 'null'
                    ? alias
                    : '';

            document.getElementById(
                'edit_phone'
            ).value =
                phone !== 'null'
                    ? phone
                    : '';

            document.getElementById(
                'edit_address'
            ).value =
                address !== 'null'
                    ? address
                    : '';

            document.getElementById(
                'edit_status'
            ).value =
                status;

            document.getElementById(
                'modalEditarCliente'
            ).classList.remove(
                'hidden'
            );
        }


        /* =========================================================
           BUSCADOR EN VIVO
        ========================================================== */

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                const buscar =
                    document.getElementById(
                        'buscarCliente'
                    );

                if (!buscar) return;

                buscar.addEventListener(
                    'input',
                    function (e) {

                        const texto =
                            e.target.value
                                .toLowerCase()
                                .trim();

                        const filas =
                            document.querySelectorAll(
                                '.fila-cliente'
                            );

                        filas.forEach(
                            fila => {

                                const contenidoFila =
                                    fila.innerText
                                        .toLowerCase();

                                if (
                                    contenidoFila.includes(
                                        texto
                                    )
                                ) {

                                    fila.style.display =
                                        '';

                                } else {

                                    fila.style.display =
                                        'none';

                                }

                            }
                        );

                    }
                );

            }
        );


        /* =========================================================
           AJUSTE DINÁMICO DE LA BARRA
        ========================================================== */

        function ajustarBarraClientes() {

            const barra =
                document.getElementById(
                    'barraAccionesClientes'
                );

            const contenido =
                document.getElementById(
                    'contenidoAccionesClientes'
                );

            const busqueda =
                document.getElementById(
                    'bloqueBusquedaClientes'
                );

            const botones =
                document.getElementById(
                    'botonesClientes'
                );

            if (
                !barra ||
                !contenido ||
                !busqueda ||
                !botones
            ) {
                return;
            }


            /* -----------------------------------------------------
               REINICIAR MODOS
            ------------------------------------------------------ */

            contenido.classList.remove(
                'modo-mediano',
                'modo-pequeno',
                'modo-muy-pequeno'
            );

            barra.classList.remove(
                'scroll-acciones'
            );


            /* -----------------------------------------------------
               CONFIGURACIÓN BASE
            ------------------------------------------------------ */

            botones.style.width =
                'auto';

            botones.style.flexWrap =
                'nowrap';

            botones.style.flexShrink =
                '1';

            contenido.style.flexWrap =
                'nowrap';


            /* -----------------------------------------------------
               ANCHO REAL DE LA BARRA
            ------------------------------------------------------ */

            const anchoDisponible =
                barra.clientWidth;


            /* -----------------------------------------------------
               NORMAL
            ------------------------------------------------------ */

            if (
                anchoDisponible >= 900
            ) {

                return;

            }


            /* -----------------------------------------------------
               MEDIANO
            ------------------------------------------------------ */

            contenido.classList.add(
                'modo-mediano'
            );

            if (
                anchoDisponible >= 650
            ) {

                return;

            }


            /* -----------------------------------------------------
               PEQUEÑO
            ------------------------------------------------------ */

            contenido.classList.add(
                'modo-pequeno'
            );

            if (
                anchoDisponible >= 480
            ) {

                return;

            }


            /* -----------------------------------------------------
               MUY PEQUEÑO
            ------------------------------------------------------ */

            contenido.classList.add(
                'modo-muy-pequeno'
            );


            /* -----------------------------------------------------
               EXTREMO
            ------------------------------------------------------ */

            if (
                anchoDisponible < 360
            ) {

                barra.classList.add(
                    'scroll-acciones'
                );

            }

        }


        /* =========================================================
           AJUSTAR TABLA AL ANCHO REAL DISPONIBLE
        ========================================================== */

        function ajustarTablaClientes() {

            const contenedor =
                document.getElementById(
                    'scrollTablaClientes'
                );

            const tabla =
                document.getElementById(
                    'tablaClientes'
                );

            if (
                !contenedor ||
                !tabla
            ) {
                return;
            }


            /* -----------------------------------------------------
               REINICIAR COMPACTACIÓN
            ------------------------------------------------------ */

            tabla.classList.remove(
                'tabla-compacta',
                'tabla-muy-compacta'
            );


            /*
             * Por defecto NO hay scroll horizontal.
             */
            contenedor.style.overflowX =
                'hidden';


            const ancho =
                contenedor.clientWidth;


            /* -----------------------------------------------------
               PRIMER INTENTO
               
               Tamaño normal.
            ------------------------------------------------------ */

            if (
                tabla.scrollWidth <=
                contenedor.clientWidth + 2
            ) {

                return;

            }


            /* -----------------------------------------------------
               SEGUNDO INTENTO
               
               Reducir fuente y padding.
            ------------------------------------------------------ */

            tabla.classList.add(
                'tabla-compacta'
            );


            if (
                tabla.scrollWidth <=
                contenedor.clientWidth + 2
            ) {

                return;

            }


            /* -----------------------------------------------------
               TERCER INTENTO
               
               Reducir todavía más.
            ------------------------------------------------------ */

            tabla.classList.remove(
                'tabla-compacta'
            );

            tabla.classList.add(
                'tabla-muy-compacta'
            );


            if (
                tabla.scrollWidth <=
                contenedor.clientWidth + 2
            ) {

                return;

            }


            /* -----------------------------------------------------
               ÚLTIMO RECURSO
               
               Solo ventanas MUY pequeñas.
            ------------------------------------------------------ */

            if (
                ancho <= 480
            ) {

                contenedor.style.overflowX =
                    'auto';

            } else {

                /*
                 * Nunca scroll en medianas/grandes.
                 */
                contenedor.style.overflowX =
                    'hidden';

            }

        }


        /* =========================================================
           OBSERVADOR DEL ANCHO REAL
        ========================================================== */

        function iniciarObservadorClientes() {

            const barra =
                document.getElementById(
                    'barraAccionesClientes'
                );

            const tablaContenedor =
                document.getElementById(
                    'scrollTablaClientes'
                );

            if (!barra) return;


            ajustarBarraClientes();

            ajustarTablaClientes();


            if (
                typeof ResizeObserver !==
                'undefined'
            ) {

                const observer =
                    new ResizeObserver(
                        function () {

                            ajustarBarraClientes();

                            ajustarTablaClientes();

                        }
                    );


                /*
                 * Observamos la barra de acciones.
                 */
                observer.observe(
                    barra
                );


                /*
                 * Observamos el contenedor real
                 * de la tabla.
                 */
                if (tablaContenedor) {

                    observer.observe(
                        tablaContenedor
                    );

                }

            } else {

                window.addEventListener(
                    'resize',
                    function () {

                        ajustarBarraClientes();

                        ajustarTablaClientes();

                    }
                );

            }

        }


        /* =========================================================
           INICIALIZACIÓN
        ========================================================== */

        document.addEventListener(
            'DOMContentLoaded',
            function () {

                iniciarObservadorClientes();

            }
        );

    </script>

</x-app-layout>
