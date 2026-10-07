<x-app-layout>

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <x-slot name="header">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Directorio de Clientes
            </h2>

            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">
                Gestión de clientes, información de contacto y cuentas asociadas.
            </p>
        </div>
    </x-slot>


    {{-- =========================================================
        CONTENIDO PRINCIPAL
    ========================================================== --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6">

        <div
            id="tarjetaClientes"
            class="bg-white dark:bg-gray-800 shadow-sm sm:rounded-lg border border-gray-200 dark:border-gray-700 overflow-hidden"
        >

            {{-- =================================================
                BARRA DE ACCIONES
            ================================================== --}}
            <div
                id="barraAccionesClientes"
                class="border-b border-gray-200 dark:border-gray-700"
            >

                <div id="contenidoAccionesClientes">

                    {{-- =================================================
                        BLOQUE PRINCIPAL INSEPARABLE

                        BUSCAR + EXCEL + PDF

                        ESTE BLOQUE JAMÁS SE DIVIDE.
                    ================================================== --}}
                    <div id="filaPrincipalAccionesClientes">

                        {{-- BUSCADOR --}}
                        <div
                            id="bloqueBusquedaClientes"
                            class="relative"
                        >
                            <span
                                class="absolute inset-y-0 left-0 flex items-center pointer-events-none text-gray-400"
                                id="iconoBusquedaClientes"
                            >
                                🔍
                            </span>

                            <input
                                type="text"
                                id="buscarCliente"
                                placeholder="Buscar cliente..."
                                autocomplete="off"
                                class="w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-200 rounded-lg focus:ring-indigo-500 focus:border-indigo-500"
                            >
                        </div>


                        {{-- =================================================
                            EXCEL + PDF

                            ESTE GRUPO NUNCA HACE WRAP
                        ================================================== --}}
                        <div id="grupoExportacionesClientes">

                            <a
                                href="{{ route('clients.export.excel') }}"
                                id="botonExcelClientes"
                                class="boton-exportacion boton-excel inline-flex items-center justify-center rounded-lg bg-green-600 hover:bg-green-700 text-white font-medium transition"
                            >
                                <span class="iconoExportacion">📊</span>
                                <span class="textoExportacion">Excel</span>
                            </a>

                            <a
                                href="{{ route('clients.export.pdf') }}"
                                id="botonPdfClientes"
                                class="boton-exportacion boton-pdf inline-flex items-center justify-center rounded-lg bg-red-600 hover:bg-red-700 text-white font-medium transition"
                            >
                                <span class="iconoExportacion">📄</span>
                                <span class="textoExportacion">PDF</span>
                            </a>

                        </div>

                    </div>


                    {{-- =================================================
                        NUEVO CLIENTE

                        ÚNICO ELEMENTO QUE PUEDE CAMBIAR DE FILA
                    ================================================== --}}
                    <div id="contenedorNuevoCliente">

                        <button
                            type="button"
                            id="botonNuevoCliente"
                            onclick="abrirModalNuevoCliente()"
                            class="inline-flex items-center justify-center rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white font-medium transition whitespace-nowrap"
                        >
                            <span class="mr-1">＋</span>
                            Nuevo Cliente
                        </button>

                    </div>

                </div>

            </div>


            {{-- =================================================
                TABLA
            ================================================== --}}
            <div
                id="scrollTablaClientes"
                class="overflow-x-auto"
            >

                <table
                    id="tablaClientes"
                    class="w-full text-sm text-left text-gray-600 dark:text-gray-300"
                >

                    <thead
                        class="text-xs uppercase bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300"
                    >
                        <tr>

                            <th
                                scope="col"
                                class="px-4 py-3 columna-cliente"
                            >
                                Cliente
                            </th>

                            <th
                                scope="col"
                                class="px-4 py-3 columna-alias"
                            >
                                Alias
                            </th>

                            <th
                                scope="col"
                                class="px-4 py-3 columna-direccion"
                            >
                                Dirección
                            </th>

                            <th
                                scope="col"
                                class="px-4 py-3 columna-telefono"
                            >
                                Teléfono
                            </th>

                            <th
                                scope="col"
                                class="px-4 py-3 columna-estatus"
                            >
                                Estatus
                            </th>

                            <th
                                scope="col"
                                class="px-4 py-3 columna-acciones"
                            >
                                Acciones
                            </th>

                        </tr>
                    </thead>


                    <tbody id="cuerpoTablaClientes">

                        @forelse($clients as $client)

                            <tr
                                class="filaCliente border-b border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600"
                                data-nombre="{{ strtolower($client->name ?? '') }}"
                                data-alias="{{ strtolower($client->alias ?? '') }}"
                                data-direccion="{{ strtolower($client->address ?? '') }}"
                                data-telefono="{{ strtolower($client->phone ?? '') }}"
                                data-estatus="{{ strtolower($client->status ?? '') }}"
                            >

                                {{-- CLIENTE --}}
                                <td class="px-4 py-3 columna-cliente">
                                    <div class="font-medium text-gray-900 dark:text-white nombreCliente">
                                        {{ $client->name }}
                                    </div>
                                </td>


                                {{-- ALIAS --}}
                                <td class="px-4 py-3 columna-alias">
                                    {{ $client->alias ?: '—' }}
                                </td>


                                {{-- DIRECCIÓN --}}
                                <td class="px-4 py-3 columna-direccion">
                                    {{ $client->address ?: '—' }}
                                </td>


                                {{-- TELÉFONO --}}
                                <td class="px-4 py-3 columna-telefono">
                                    {{ $client->phone ?: '—' }}
                                </td>


                                {{-- ESTATUS --}}
                                <td class="px-4 py-3 columna-estatus">

                                    @if(($client->status ?? '') === 'activo')

                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            Activo
                                        </span>

                                    @elseif(($client->status ?? '') === 'inactivo')

                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            Inactivo
                                        </span>

                                    @else

                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                            {{ $client->status ?: '—' }}
                                        </span>

                                    @endif

                                </td>


                                {{-- ACCIONES --}}
                                <td class="px-4 py-3 columna-acciones">

                                    <div class="flex items-center gap-2 whitespace-nowrap">

                                        <a
                                            href="{{ route('clients.accounts', $client->id) }}"
                                            class="inline-flex items-center px-3 py-1.5 rounded-md bg-indigo-600 hover:bg-indigo-700 text-white text-xs font-medium transition"
                                        >
                                            Cuentas
                                        </a>

                                        <button
                                            type="button"
                                            onclick="abrirModalEditarCliente(
                                                {{ $client->id }},
                                                @js($client->name),
                                                @js($client->alias),
                                                @js($client->phone),
                                                @js($client->address),
                                                @js($client->status)
                                            )"
                                            class="inline-flex items-center px-3 py-1.5 rounded-md bg-gray-600 hover:bg-gray-700 text-white text-xs font-medium transition"
                                        >
                                            Editar
                                        </button>

                                    </div>

                                </td>

                            </tr>

                        @empty

                            <tr id="filaSinClientes">
                                <td
                                    colspan="6"
                                    class="px-4 py-8 text-center text-gray-500 dark:text-gray-400"
                                >
                                    No hay clientes registrados.
                                </td>
                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>


    {{-- =========================================================
        MODAL NUEVO CLIENTE
    ========================================================== --}}
    <div
        id="modalNuevoCliente"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4"
    >

        <div
            class="w-full max-w-lg bg-white dark:bg-gray-800 rounded-xl shadow-xl overflow-hidden"
        >

            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Nuevo Cliente
                    </h3>

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Registra la información del cliente.
                    </p>
                </div>

                <button
                    type="button"
                    onclick="cerrarModalNuevoCliente()"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl"
                >
                    ✕
                </button>

            </div>


            <form
                method="POST"
                action="{{ route('clients.store') }}"
            >

                @csrf

                <div class="p-6 space-y-4">

                    <div>
                        <label
                            for="name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >
                            Nombre
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="name"
                            required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>


                    <div>
                        <label
                            for="alias"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >
                            Alias
                        </label>

                        <input
                            type="text"
                            name="alias"
                            id="alias"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>


                    <div>
                        <label
                            for="phone"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >
                            Teléfono
                        </label>

                        <input
                            type="text"
                            name="phone"
                            id="phone"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>


                    <div>
                        <label
                            for="address"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >
                            Dirección
                        </label>

                        <textarea
                            name="address"
                            id="address"
                            rows="3"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                        ></textarea>
                    </div>


                    <div>
                        <label
                            for="status"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >
                            Estatus
                        </label>

                        <select
                            name="status"
                            id="status"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>

                </div>


                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-end gap-2">

                    <button
                        type="button"
                        onclick="cerrarModalNuevoCliente()"
                        class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-white text-sm font-medium"
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium"
                    >
                        Guardar Cliente
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        MODAL EDITAR CLIENTE
    ========================================================== --}}
    <div
        id="modalEditarCliente"
        class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 px-4"
    >

        <div
            class="w-full max-w-lg bg-white dark:bg-gray-800 rounded-xl shadow-xl overflow-hidden"
        >

            <div class="px-6 py-4 border-b border-gray-200 dark:border-gray-700 flex items-center justify-between">

                <div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">
                        Editar Cliente
                    </h3>

                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Modifica la información del cliente.
                    </p>
                </div>

                <button
                    type="button"
                    onclick="cerrarModalEditarCliente()"
                    class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 text-xl"
                >
                    ✕
                </button>

            </div>


            <form
                id="formEditarCliente"
                method="POST"
            >

                @csrf
                @method('PUT')

                <div class="p-6 space-y-4">

                    <div>
                        <label
                            for="edit_name"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >
                            Nombre
                        </label>

                        <input
                            type="text"
                            name="name"
                            id="edit_name"
                            required
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>


                    <div>
                        <label
                            for="edit_alias"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >
                            Alias
                        </label>

                        <input
                            type="text"
                            name="alias"
                            id="edit_alias"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>


                    <div>
                        <label
                            for="edit_phone"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >
                            Teléfono
                        </label>

                        <input
                            type="text"
                            name="phone"
                            id="edit_phone"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                        >
                    </div>


                    <div>
                        <label
                            for="edit_address"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >
                            Dirección
                        </label>

                        <textarea
                            name="address"
                            id="edit_address"
                            rows="3"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                        ></textarea>
                    </div>


                    <div>
                        <label
                            for="edit_status"
                            class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1"
                        >
                            Estatus
                        </label>

                        <select
                            name="status"
                            id="edit_status"
                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500"
                        >
                            <option value="activo">Activo</option>
                            <option value="inactivo">Inactivo</option>
                        </select>
                    </div>

                </div>


                <div class="px-6 py-4 bg-gray-50 dark:bg-gray-700/50 flex justify-end gap-2">

                    <button
                        type="button"
                        onclick="cerrarModalEditarCliente()"
                        class="px-4 py-2 rounded-lg bg-gray-200 hover:bg-gray-300 dark:bg-gray-600 dark:hover:bg-gray-500 text-gray-800 dark:text-white text-sm font-medium"
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium"
                    >
                        Guardar Cambios
                    </button>

                </div>

            </form>

        </div>

    </div>


    {{-- =========================================================
        CSS
    ========================================================== --}}
    <style>

        /* =====================================================
           BARRA PRINCIPAL
        ====================================================== */

        #barraAccionesClientes {
            width: 100%;
            min-width: 0;
            padding: 12px;
            box-sizing: border-box;
            overflow: visible;
        }


        /*
         * AQUÍ ESTÁ EL CAMBIO PRINCIPAL.
         *
         * El contenedor tiene DOS zonas:
         *
         * principal = Buscar + Excel + PDF
         * nuevo     = Nuevo Cliente
         *
         * Cuando cabe:
         *
         * Buscar | Excel | PDF | Nuevo Cliente
         *
         * Cuando no cabe:
         *
         * Buscar | Excel | PDF
         * Nuevo Cliente
         */
        #contenidoAccionesClientes {
            width: 100%;
            min-width: 0;

            display: grid;

            grid-template-columns:
                minmax(0, 1fr)
                max-content;

            grid-template-areas:
                "principal nuevo";

            column-gap: 8px;
            row-gap: 6px;

            align-items: center;

            box-sizing: border-box;
        }


        /* =====================================================
           BLOQUE PRINCIPAL

           BUSCAR + EXCEL + PDF

           ESTE BLOQUE NO PUEDE HACER WRAP.
        ====================================================== */

        #filaPrincipalAccionesClientes {
            grid-area: principal;

            width: 100%;
            min-width: 0;

            display: grid !important;

            /*
             * UNA SOLA FILA:
             *
             * columna 1 = buscador
             * columna 2 = exportaciones
             */
            grid-template-columns:
                minmax(0, 1fr)
                max-content;

            grid-template-rows:
                1fr;

            align-items: center;

            column-gap: 8px;

            box-sizing: border-box;

            /*
             * MUY IMPORTANTE:
             * nunca overflow hidden.
             */
            overflow: visible;

            white-space: nowrap;
        }


        /* =====================================================
           BUSCADOR
        ====================================================== */

        #bloqueBusquedaClientes {
            width: 100%;
            min-width: 0;

            position: relative;

            box-sizing: border-box;
        }


        #bloqueBusquedaClientes input {
            display: block;

            width: 100%;
            min-width: 0;

            height: 38px;

            padding-left: 36px;
            padding-right: 10px;

            font-size: 14px;

            box-sizing: border-box;

            white-space: nowrap;

            overflow: hidden;
            text-overflow: ellipsis;
        }


        #iconoBusquedaClientes {
            left: 12px;
            font-size: 13px;
            z-index: 2;
        }


        /* =====================================================
           EXCEL + PDF

           BLOQUE COMPLETAMENTE FIJO.

           JAMÁS SE DIVIDE.
        ====================================================== */

        #grupoExportacionesClientes {
            width: max-content;
            min-width: max-content;

            display: flex !important;

            flex-direction: row !important;

            flex-wrap: nowrap !important;

            align-items: center;

            justify-content: flex-start;

            gap: 6px;

            white-space: nowrap;

            box-sizing: border-box;

            flex-shrink: 0;

            overflow: visible;
        }


        #grupoExportacionesClientes .boton-exportacion {
            flex: 0 0 auto !important;

            display: inline-flex !important;

            align-items: center;
            justify-content: center;

            width: max-content !important;
            min-width: max-content !important;

            white-space: nowrap !important;

            box-sizing: border-box;

            flex-shrink: 0 !important;
        }


        /* =====================================================
           BOTONES EXPORTACIÓN NORMAL
        ====================================================== */

        .boton-exportacion {
            height: 38px;

            padding-left: 12px;
            padding-right: 12px;

            font-size: 13px;

            line-height: 1;

            white-space: nowrap !important;
        }


        .iconoExportacion {
            margin-right: 5px;

            flex: 0 0 auto;

            white-space: nowrap;
        }


        .textoExportacion {
            white-space: nowrap !important;

            display: inline-block;
        }


        /* =====================================================
           NUEVO CLIENTE
        ====================================================== */

        #contenedorNuevoCliente {
            grid-area: nuevo;

            width: max-content;
            min-width: max-content;

            display: flex;

            align-items: center;
            justify-content: flex-end;

            box-sizing: border-box;

            flex-shrink: 0;
        }


        #botonNuevoCliente {
            display: inline-flex;

            align-items: center;
            justify-content: center;

            flex: 0 0 auto;

            width: max-content;
            min-width: max-content;

            white-space: nowrap;

            height: 38px;

            padding-left: 14px;
            padding-right: 14px;

            font-size: 14px;
        }


        /* =====================================================
           NUEVO CLIENTE EN SEGUNDA FILA

           SOLAMENTE ESTE ELEMENTO CAMBIA.
        ====================================================== */

        #contenidoAccionesClientes.nuevo-segunda-fila {
            grid-template-columns:
                minmax(0, 1fr);

            grid-template-areas:
                "principal"
                "nuevo";
        }


        #contenidoAccionesClientes.nuevo-segunda-fila
        #contenedorNuevoCliente {
            width: 100%;

            justify-content: flex-end;
        }


        /* =====================================================
           MODO COMPACTO
        ====================================================== */

        #barraAccionesClientes.modo-compacto {
            padding: 10px;
        }


        #barraAccionesClientes.modo-compacto
        #filaPrincipalAccionesClientes {
            column-gap: 6px;
        }


        #barraAccionesClientes.modo-compacto
        #bloqueBusquedaClientes input {
            height: 35px;

            font-size: 12px;

            padding-left: 32px;
            padding-right: 8px;
        }


        #barraAccionesClientes.modo-compacto
        #iconoBusquedaClientes {
            left: 10px;
            font-size: 11px;
        }


        #barraAccionesClientes.modo-compacto
        #grupoExportacionesClientes {
            gap: 5px;
        }


        #barraAccionesClientes.modo-compacto
        .boton-exportacion {
            height: 35px;

            padding-left: 9px;
            padding-right: 9px;

            font-size: 11px;
        }


        #barraAccionesClientes.modo-compacto
        #botonNuevoCliente {
            height: 35px;

            padding-left: 10px;
            padding-right: 10px;

            font-size: 11px;
        }


        /* =====================================================
           MODO PEQUEÑO
        ====================================================== */

        #barraAccionesClientes.modo-pequeno {
            padding: 8px;
        }


        #barraAccionesClientes.modo-pequeno
        #filaPrincipalAccionesClientes {
            column-gap: 4px;
        }


        #barraAccionesClientes.modo-pequeno
        #bloqueBusquedaClientes input {
            height: 32px;

            font-size: 11px;

            padding-left: 28px;
            padding-right: 6px;
        }


        #barraAccionesClientes.modo-pequeno
        #iconoBusquedaClientes {
            left: 8px;
            font-size: 10px;
        }


        #barraAccionesClientes.modo-pequeno
        #grupoExportacionesClientes {
            gap: 4px;
        }


        #barraAccionesClientes.modo-pequeno
        .boton-exportacion {
            height: 32px;

            padding-left: 7px;
            padding-right: 7px;

            font-size: 10px;
        }


        #barraAccionesClientes.modo-pequeno
        .iconoExportacion {
            margin-right: 3px;
        }


        #barraAccionesClientes.modo-pequeno
        #botonNuevoCliente {
            height: 32px;

            padding-left: 8px;
            padding-right: 8px;

            font-size: 10px;
        }


        /* =====================================================
           MODO MUY PEQUEÑO
        ====================================================== */

        #barraAccionesClientes.modo-muy-pequeno {
            padding: 6px;
        }


        #barraAccionesClientes.modo-muy-pequeno
        #filaPrincipalAccionesClientes {
            column-gap: 3px;
        }


        #barraAccionesClientes.modo-muy-pequeno
        #bloqueBusquedaClientes input {
            height: 29px;

            font-size: 9px;

            padding-left: 23px;
            padding-right: 4px;
        }


        #barraAccionesClientes.modo-muy-pequeno
        #iconoBusquedaClientes {
            left: 6px;
            font-size: 8px;
        }


        #barraAccionesClientes.modo-muy-pequeno
        #grupoExportacionesClientes {
            gap: 3px;
        }


        #barraAccionesClientes.modo-muy-pequeno
        .boton-exportacion {
            height: 29px;

            padding-left: 5px;
            padding-right: 5px;

            font-size: 8px;
        }


        #barraAccionesClientes.modo-muy-pequeno
        .iconoExportacion {
            display: none;
        }


        #barraAccionesClientes.modo-muy-pequeno
        #botonNuevoCliente {
            height: 29px;

            padding-left: 6px;
            padding-right: 6px;

            font-size: 9px;
        }


        /* =====================================================
           TABLA - BASE
        ====================================================== */

        #scrollTablaClientes {
            width: 100%;
            max-width: 100%;

            overflow-x: auto;
            overflow-y: auto;

            max-height: 64vh;

            min-width: 0;
        }


        #tablaClientes {
            min-width: 1000px;
            border-collapse: collapse;
        }


        #tablaClientes th,
        #tablaClientes td {
            vertical-align: middle;
        }


        /* =====================================================
           TABLA MEDIANA
        ====================================================== */

        #scrollTablaClientes.modo-tabla-mediana
        #tablaClientes {
            min-width: 850px;
        }


        #scrollTablaClientes.modo-tabla-mediana
        .columna-alias {
            display: none;
        }


        /* =====================================================
           TABLA COMPACTA
        ====================================================== */

        #scrollTablaClientes.modo-tabla-compacta
        #tablaClientes {
            min-width: 700px;
        }


        #scrollTablaClientes.modo-tabla-compacta
        .columna-alias,
        #scrollTablaClientes.modo-tabla-compacta
        .columna-telefono {
            display: none;
        }


        #scrollTablaClientes.modo-tabla-compacta
        #tablaClientes th,
        #scrollTablaClientes.modo-tabla-compacta
        #tablaClientes td {
            padding: 8px 10px;
            font-size: 12px;
        }


        /* =====================================================
           MÓVIL
        ====================================================== */

        #scrollTablaClientes.modo-movil
        #tablaClientes {
            min-width: 620px;
        }


        #scrollTablaClientes.modo-movil
        .columna-alias,
        #scrollTablaClientes.modo-movil
        .columna-telefono {
            display: none;
        }


        #scrollTablaClientes.modo-movil
        #tablaClientes th,
        #scrollTablaClientes.modo-movil
        #tablaClientes td {
            padding: 7px 8px;
            font-size: 11px;
        }


        /* =====================================================
           MÓVIL MUY PEQUEÑO
        ====================================================== */

        #scrollTablaClientes.modo-movil-muy-pequeno
        #tablaClientes {
            min-width: 560px;
        }


        #scrollTablaClientes.modo-movil-muy-pequeno
        #tablaClientes th,
        #scrollTablaClientes.modo-movil-muy-pequeno
        #tablaClientes td {
            padding: 6px 7px;
            font-size: 10px;
        }


        /* =====================================================
           BÚSQUEDA
        ====================================================== */

        .filaCliente.ocultoBusqueda {
            display: none;
        }


        /* =====================================================
           MODALES
        ====================================================== */

        @media (max-width: 500px) {

            #modalNuevoCliente > div,
            #modalEditarCliente > div {
                max-height: 92vh;
                overflow-y: auto;
            }

        }

    </style>


    {{-- =========================================================
        JAVASCRIPT
    ========================================================== --}}
    <script>

        document.addEventListener('DOMContentLoaded', function () {

            const barraAcciones =
                document.getElementById('barraAccionesClientes');

            const contenidoAcciones =
                document.getElementById('contenidoAccionesClientes');

            const filaPrincipal =
                document.getElementById('filaPrincipalAccionesClientes');

            const bloqueBusqueda =
                document.getElementById('bloqueBusquedaClientes');

            const grupoExportaciones =
                document.getElementById('grupoExportacionesClientes');

            const contenedorNuevo =
                document.getElementById('contenedorNuevoCliente');

            const botonNuevo =
                document.getElementById('botonNuevoCliente');

            const scrollTabla =
                document.getElementById('scrollTablaClientes');

            const tarjeta =
                document.getElementById('tarjetaClientes');

            const inputBuscar =
                document.getElementById('buscarCliente');


            /* =====================================================
               AJUSTAR TAMAÑO DE LA BARRA
            ====================================================== */

            function ajustarBarraAcciones() {

                if (
                    !barraAcciones ||
                    !contenidoAcciones ||
                    !filaPrincipal ||
                    !grupoExportaciones ||
                    !contenedorNuevo ||
                    !botonNuevo
                ) {
                    return;
                }


                /*
                 * ==================================================
                 * 1. ANCHO REAL DEL CONTENEDOR
                 *
                 * No usamos window.innerWidth.
                 *
                 * Esto permite detectar correctamente cuando
                 * el menú lateral ocupa espacio.
                 * ==================================================
                 */

                const ancho =
                    barraAcciones.clientWidth;


                /*
                 * ==================================================
                 * 2. TAMAÑO PROGRESIVO
                 * ==================================================
                 */

                barraAcciones.classList.remove(
                    'modo-compacto',
                    'modo-pequeno',
                    'modo-muy-pequeno'
                );


                if (ancho >= 1050) {

                    // Normal

                } else if (ancho >= 800) {

                    barraAcciones.classList.add(
                        'modo-compacto'
                    );

                } else if (ancho >= 600) {

                    barraAcciones.classList.add(
                        'modo-pequeno'
                    );

                } else {

                    barraAcciones.classList.add(
                        'modo-muy-pequeno'
                    );

                }


                /*
                 * ==================================================
                 * 3. PRIMERO INTENTAMOS TODO EN UNA SOLA FILA
                 *
                 * Buscar + Excel + PDF siguen siendo SIEMPRE
                 * una sola unidad.
                 * ==================================================
                 */

                contenidoAcciones.classList.remove(
                    'nuevo-segunda-fila'
                );


                requestAnimationFrame(function () {

                    /*
                     * Ancho total disponible de la barra.
                     */
                    const disponible =
                        contenidoAcciones.clientWidth;


                    /*
                     * Ancho real del grupo Excel + PDF.
                     *
                     * Este ancho NO se puede reducir.
                     */
                    const anchoExportaciones =
                        grupoExportaciones.getBoundingClientRect().width;


                    /*
                     * Ancho real del botón Nuevo Cliente.
                     *
                     * Medimos el botón, NO el contenedor.
                     */
                    const anchoNuevo =
                        botonNuevo.getBoundingClientRect().width;


                    /*
                     * Gap interno entre:
                     *
                     * Buscar | Excel/PDF
                     */
                    const estiloFila =
                        window.getComputedStyle(
                            filaPrincipal
                        );


                    const gapInterno =
                        parseFloat(
                            estiloFila.columnGap
                        ) || 0;


                    /*
                     * Gap externo entre:
                     *
                     * bloque principal | Nuevo
                     */
                    const estiloContenido =
                        window.getComputedStyle(
                            contenidoAcciones
                        );


                    const gapExterno =
                        parseFloat(
                            estiloContenido.columnGap
                        ) || 0;


                    /*
                     * ==================================================
                     * ANCHO MÍNIMO QUE LE PERMITIREMOS AL BUSCADOR
                     *
                     * El buscador puede reducirse.
                     *
                     * Pero no queremos que llegue a desaparecer
                     * cuando todavía podemos bajar Nuevo Cliente.
                     * ==================================================
                     */

                    let minimoBuscador;


                    if (ancho >= 1050) {

                        minimoBuscador = 180;

                    } else if (ancho >= 800) {

                        minimoBuscador = 140;

                    } else if (ancho >= 600) {

                        minimoBuscador = 100;

                    } else {

                        minimoBuscador = 60;

                    }


                    /*
                     * ==================================================
                     * ESPACIO NECESARIO PARA MANTENER TODO JUNTO
                     *
                     * Buscar
                     * +
                     * gap
                     * +
                     * Excel/PDF
                     * +
                     * gap
                     * +
                     * Nuevo Cliente
                     * ==================================================
                     */

                    const necesario =
                        minimoBuscador +
                        gapInterno +
                        anchoExportaciones +
                        gapExterno +
                        anchoNuevo;


                    /*
                     * ==================================================
                     * DECISIÓN
                     *
                     * SI CABE:
                     *
                     * Buscar | Excel | PDF | Nuevo Cliente
                     *
                     * SI NO CABE:
                     *
                     * Buscar | Excel | PDF
                     *
                     * Nuevo Cliente
                     *
                     * NUNCA SEPARARÁ EXCEL/PDF.
                     * ==================================================
                     */

                    if (
                        necesario >
                        disponible
                    ) {

                        contenidoAcciones.classList.add(
                            'nuevo-segunda-fila'
                        );

                    }

                });

            }


            /* =====================================================
               AJUSTE RESPONSIVE DE TABLA
            ====================================================== */

            function ajustarTablaResponsive() {

                if (!scrollTabla) {
                    return;
                }


                const ancho =
                    scrollTabla.clientWidth;


                scrollTabla.classList.remove(
                    'modo-tabla-mediana',
                    'modo-tabla-compacta',
                    'modo-movil',
                    'modo-movil-muy-pequeno'
                );


                if (ancho >= 1050) {

                    // Tabla completa

                } else if (ancho >= 760) {

                    scrollTabla.classList.add(
                        'modo-tabla-mediana'
                    );

                } else if (ancho >= 500) {

                    scrollTabla.classList.add(
                        'modo-tabla-compacta'
                    );

                } else {

                    scrollTabla.classList.add(
                        'modo-movil'
                    );


                    if (ancho < 430) {

                        scrollTabla.classList.add(
                            'modo-movil-muy-pequeno'
                        );

                    }

                }

            }


            /* =====================================================
               BÚSQUEDA
            ====================================================== */

            if (inputBuscar) {

                inputBuscar.addEventListener(
                    'input',
                    function () {

                        const texto =
                            this.value
                                .toLowerCase()
                                .trim();


                        const filas =
                            document.querySelectorAll(
                                '#cuerpoTablaClientes .filaCliente'
                            );


                        let encontrados = 0;


                        filas.forEach(function (fila) {

                            const nombre =
                                fila.dataset.nombre || '';

                            const alias =
                                fila.dataset.alias || '';

                            const direccion =
                                fila.dataset.direccion || '';

                            const telefono =
                                fila.dataset.telefono || '';

                            const estatus =
                                fila.dataset.estatus || '';


                            const contenido =
                                (
                                    nombre +
                                    ' ' +
                                    alias +
                                    ' ' +
                                    direccion +
                                    ' ' +
                                    telefono +
                                    ' ' +
                                    estatus
                                ).toLowerCase();


                            const coincide =
                                contenido.includes(texto);


                            if (coincide) {

                                fila.classList.remove(
                                    'ocultoBusqueda'
                                );

                                encontrados++;

                            } else {

                                fila.classList.add(
                                    'ocultoBusqueda'
                                );

                            }

                        });


                        actualizarMensajeSinResultados(
                            encontrados,
                            texto
                        );

                    }
                );

            }


            /* =====================================================
               SIN RESULTADOS
            ====================================================== */

            function actualizarMensajeSinResultados(
                encontrados,
                texto
            ) {

                let filaMensaje =
                    document.getElementById(
                        'filaSinResultadosBusqueda'
                    );


                if (
                    encontrados === 0 &&
                    texto !== ''
                ) {

                    if (!filaMensaje) {

                        filaMensaje =
                            document.createElement('tr');

                        filaMensaje.id =
                            'filaSinResultadosBusqueda';

                        filaMensaje.innerHTML = `
                            <td
                                colspan="6"
                                class="px-4 py-8 text-center text-gray-500 dark:text-gray-400"
                            >
                                No se encontraron clientes.
                            </td>
                        `;


                        const cuerpo =
                            document.getElementById(
                                'cuerpoTablaClientes'
                            );


                        if (cuerpo) {

                            cuerpo.appendChild(
                                filaMensaje
                            );

                        }

                    }

                } else {

                    if (filaMensaje) {

                        filaMensaje.remove();

                    }

                }

            }


            /* =====================================================
               MODAL NUEVO CLIENTE
            ====================================================== */

            window.abrirModalNuevoCliente =
                function () {

                    const modal =
                        document.getElementById(
                            'modalNuevoCliente'
                        );


                    if (!modal) {
                        return;
                    }


                    modal.classList.remove(
                        'hidden'
                    );

                    modal.classList.add(
                        'flex'
                    );


                    document.body.classList.add(
                        'overflow-hidden'
                    );

                };


            window.cerrarModalNuevoCliente =
                function () {

                    const modal =
                        document.getElementById(
                            'modalNuevoCliente'
                        );


                    if (!modal) {
                        return;
                    }


                    modal.classList.add(
                        'hidden'
                    );

                    modal.classList.remove(
                        'flex'
                    );


                    document.body.classList.remove(
                        'overflow-hidden'
                    );

                };


            /* =====================================================
               MODAL EDITAR CLIENTE
            ====================================================== */

            window.abrirModalEditarCliente =
                function (
                    id,
                    name,
                    alias,
                    phone,
                    address,
                    status
                ) {

                    const modal =
                        document.getElementById(
                            'modalEditarCliente'
                        );


                    const formulario =
                        document.getElementById(
                            'formEditarCliente'
                        );


                    if (
                        !modal ||
                        !formulario
                    ) {
                        return;
                    }


                    formulario.action =
                        '/clients/' + id;


                    document.getElementById(
                        'edit_name'
                    ).value =
                        name || '';


                    document.getElementById(
                        'edit_alias'
                    ).value =
                        alias || '';


                    document.getElementById(
                        'edit_phone'
                    ).value =
                        phone || '';


                    document.getElementById(
                        'edit_address'
                    ).value =
                        address || '';


                    document.getElementById(
                        'edit_status'
                    ).value =
                        status || 'activo';


                    modal.classList.remove(
                        'hidden'
                    );

                    modal.classList.add(
                        'flex'
                    );


                    document.body.classList.add(
                        'overflow-hidden'
                    );

                };


            window.cerrarModalEditarCliente =
                function () {

                    const modal =
                        document.getElementById(
                            'modalEditarCliente'
                        );


                    if (!modal) {
                        return;
                    }


                    modal.classList.add(
                        'hidden'
                    );

                    modal.classList.remove(
                        'flex'
                    );


                    document.body.classList.remove(
                        'overflow-hidden'
                    );

                };


            /* =====================================================
               CERRAR MODAL AL HACER CLICK AFUERA
            ====================================================== */

            const modalNuevo =
                document.getElementById(
                    'modalNuevoCliente'
                );


            if (modalNuevo) {

                modalNuevo.addEventListener(
                    'click',
                    function (e) {

                        if (
                            e.target ===
                            modalNuevo
                        ) {

                            cerrarModalNuevoCliente();

                        }

                    }
                );

            }


            const modalEditar =
                document.getElementById(
                    'modalEditarCliente'
                );


            if (modalEditar) {

                modalEditar.addEventListener(
                    'click',
                    function (e) {

                        if (
                            e.target ===
                            modalEditar
                        ) {

                            cerrarModalEditarCliente();

                        }

                    }
                );

            }


            /* =====================================================
               ESC
            ====================================================== */

            document.addEventListener(
                'keydown',
                function (e) {

                    if (
                        e.key !==
                        'Escape'
                    ) {
                        return;
                    }


                    cerrarModalNuevoCliente();
                    cerrarModalEditarCliente();

                }
            );


            /* =====================================================
               RESIZE OBSERVER
            ====================================================== */

            if (
                typeof ResizeObserver !==
                'undefined'
            ) {

                const observer =
                    new ResizeObserver(
                        function () {

                            ajustarBarraAcciones();
                            ajustarTablaResponsive();

                        }
                    );


                if (barraAcciones) {

                    observer.observe(
                        barraAcciones
                    );

                }


                if (scrollTabla) {

                    observer.observe(
                        scrollTabla
                    );

                }


                if (tarjeta) {

                    observer.observe(
                        tarjeta
                    );

                }

            } else {

                window.addEventListener(
                    'resize',
                    function () {

                        ajustarBarraAcciones();
                        ajustarTablaResponsive();

                    }
                );

            }


            /* =====================================================
               INICIALIZACIÓN
            ====================================================== */

            ajustarBarraAcciones();

            ajustarTablaResponsive();


            requestAnimationFrame(
                function () {

                    ajustarBarraAcciones();

                    ajustarTablaResponsive();

                }
            );

        });

    </script>

</x-app-layout>