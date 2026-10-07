
<x-app-layout>

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <x-slot name="header">
        <div class="flex justify-between items-center gap-3">
            
            <h2 class="font-bold text-xl text-gray-800 dark:text-gray-100 leading-tight">
                {{ __('Lista de Usuarios') }}
            </h2>

            <a href="{{ route('usuarios.crear') }}"
                class="inline-flex items-center px-4 py-2
                       bg-indigo-600 hover:bg-indigo-500 active:bg-indigo-700
                       text-white text-xs font-semibold uppercase tracking-widest
                       rounded-xl shadow-sm hover:shadow transition
                       whitespace-nowrap shrink-0">

                <svg class="w-4 h-4 mr-2 shrink-0"
                     fill="none"
                     stroke="currentColor"
                     viewBox="0 0 24 24">

                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M12 4v16m8-8H4" />
                </svg>

                Nuevo Usuario
            </a>

        </div>
    </x-slot>


    {{-- =========================================================
        ESTILOS RESPONSIVOS
    ========================================================== --}}
    <style>

        /*
        ============================================================
        BASE
        ============================================================
        */

        #contenedorUsuarios {
            width: 100%;
            min-width: 0;
        }

        #tarjetaUsuarios {
            width: 100%;
            min-width: 0;
        }

        #scrollTablaUsuarios {
            width: 100%;
            min-width: 0;
            overflow-x: auto;
            overflow-y: auto;
            scrollbar-width: thin;
        }

        #tablaUsuarios {
            width: 100%;
            min-width: 0;
            table-layout: auto;
            border-collapse: separate;
            border-spacing: 0;
        }

        #tablaUsuarios th,
        #tablaUsuarios td {
            vertical-align: middle;
        }


        /*
        ============================================================
        TEXTO DE CELDAS
        ============================================================
        */

        #tablaUsuarios .contenido-celda {
            max-width: 100%;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }


        /*
        ============================================================
        PANTALLAS MEDIANAS
        ============================================================
        */

        @media (max-width: 1100px) {

            #tablaUsuarios th,
            #tablaUsuarios td {
                padding-left: 0.9rem;
                padding-right: 0.9rem;
            }

            #tablaUsuarios tbody tr {
                font-size: 0.8125rem;
            }

            #tablaUsuarios thead {
                font-size: 0.6875rem;
            }

            #tablaUsuarios .badge-usuario {
                padding-left: 0.55rem;
                padding-right: 0.55rem;
                padding-top: 0.22rem;
                padding-bottom: 0.22rem;
                font-size: 0.6875rem;
            }

            #tablaUsuarios .boton-editar {
                padding-left: 0.65rem;
                padding-right: 0.65rem;
                padding-top: 0.35rem;
                padding-bottom: 0.35rem;
                font-size: 0.6875rem;
            }
        }


        /*
        ============================================================
        PANTALLAS MEDIANAS PEQUEÑAS
        ============================================================
        */

        @media (max-width: 900px) {

            #tablaUsuarios th,
            #tablaUsuarios td {
                padding-left: 0.65rem;
                padding-right: 0.65rem;
                padding-top: 0.65rem;
                padding-bottom: 0.65rem;
            }

            #tablaUsuarios tbody tr {
                font-size: 0.75rem;
            }

            #tablaUsuarios thead {
                font-size: 0.625rem;
                letter-spacing: 0.025em;
            }

            #tablaUsuarios .badge-usuario {
                padding-left: 0.45rem;
                padding-right: 0.45rem;
                padding-top: 0.2rem;
                padding-bottom: 0.2rem;
                font-size: 0.625rem;
            }

            #tablaUsuarios .punto-estatus {
                width: 0.3rem;
                height: 0.3rem;
                margin-right: 0.35rem;
            }

            #tablaUsuarios .boton-editar {
                padding-left: 0.55rem;
                padding-right: 0.55rem;
                padding-top: 0.3rem;
                padding-bottom: 0.3rem;
                font-size: 0.625rem;
            }
        }


        /*
        ============================================================
        PANTALLAS PEQUEÑAS
        ============================================================
        */

        @media (max-width: 700px) {

            #contenedorUsuarios {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
            }

            #tablaUsuarios th,
            #tablaUsuarios td {
                padding-left: 0.45rem;
                padding-right: 0.45rem;
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
            }

            #tablaUsuarios tbody tr {
                font-size: 0.6875rem;
            }

            #tablaUsuarios thead {
                font-size: 0.5625rem;
                letter-spacing: 0.01em;
            }

            #tablaUsuarios .badge-usuario {
                padding-left: 0.35rem;
                padding-right: 0.35rem;
                padding-top: 0.15rem;
                padding-bottom: 0.15rem;
                font-size: 0.5625rem;
                border-radius: 0.4rem;
            }

            #tablaUsuarios .boton-editar {
                padding-left: 0.45rem;
                padding-right: 0.45rem;
                padding-top: 0.25rem;
                padding-bottom: 0.25rem;
                font-size: 0.5625rem;
            }

            #tablaUsuarios .punto-estatus {
                width: 0.25rem;
                height: 0.25rem;
                margin-right: 0.25rem;
            }
        }


        /*
        ============================================================
        MÓVILES
        ============================================================
        */

        @media (max-width: 500px) {

            #contenedorUsuarios {
                padding-left: 0.25rem;
                padding-right: 0.25rem;
            }

            #tablaUsuarios th,
            #tablaUsuarios td {
                padding-left: 0.35rem;
                padding-right: 0.35rem;
                padding-top: 0.4rem;
                padding-bottom: 0.4rem;
            }

            #tablaUsuarios tbody tr {
                font-size: 0.625rem;
            }

            #tablaUsuarios thead {
                font-size: 0.5rem;
            }

            #tablaUsuarios .badge-usuario {
                padding-left: 0.3rem;
                padding-right: 0.3rem;
                padding-top: 0.12rem;
                padding-bottom: 0.12rem;
                font-size: 0.5rem;
            }

            #tablaUsuarios .boton-editar {
                padding-left: 0.35rem;
                padding-right: 0.35rem;
                padding-top: 0.2rem;
                padding-bottom: 0.2rem;
                font-size: 0.5rem;
            }

            #tablaUsuarios .punto-estatus {
                display: none;
            }
        }


        /*
        ============================================================
        CUANDO YA NO ES POSIBLE REDUCIR MÁS
        ============================================================
        */

        @media (max-width: 430px) {

            #scrollTablaUsuarios {
                overflow-x: auto;
            }

            #tablaUsuarios {
                min-width: 650px;
            }
        }


        /*
        ============================================================
        ANCHO DE COLUMNAS
        ============================================================
        */

        #tablaUsuarios th:nth-child(1),
        #tablaUsuarios td:nth-child(1) {
            width: 6%;
        }

        #tablaUsuarios th:nth-child(2),
        #tablaUsuarios td:nth-child(2) {
            width: 16%;
        }

        #tablaUsuarios th:nth-child(3),
        #tablaUsuarios td:nth-child(3) {
            width: 22%;
        }

        #tablaUsuarios th:nth-child(4),
        #tablaUsuarios td:nth-child(4) {
            width: 21%;
        }

        #tablaUsuarios th:nth-child(5),
        #tablaUsuarios td:nth-child(5) {
            width: 11%;
        }

        #tablaUsuarios th:nth-child(6),
        #tablaUsuarios td:nth-child(6) {
            width: 12%;
        }

        #tablaUsuarios th:nth-child(7),
        #tablaUsuarios td:nth-child(7) {
            width: 12%;
        }


        /*
        ============================================================
        EN MÓVIL SE DEJA UN ANCHO MÍNIMO PARA EVITAR QUE
        LAS COLUMNAS SE APLASTEN INDEBIDAMENTE
        ============================================================
        */

        @media (max-width: 430px) {

            #tablaUsuarios th:nth-child(1),
            #tablaUsuarios td:nth-child(1) {
                width: auto;
                min-width: 45px;
            }

            #tablaUsuarios th:nth-child(2),
            #tablaUsuarios td:nth-child(2) {
                width: auto;
                min-width: 110px;
            }

            #tablaUsuarios th:nth-child(3),
            #tablaUsuarios td:nth-child(3) {
                width: auto;
                min-width: 150px;
            }

            #tablaUsuarios th:nth-child(4),
            #tablaUsuarios td:nth-child(4) {
                width: auto;
                min-width: 130px;
            }

            #tablaUsuarios th:nth-child(5),
            #tablaUsuarios td:nth-child(5) {
                width: auto;
                min-width: 80px;
            }

            #tablaUsuarios th:nth-child(6),
            #tablaUsuarios td:nth-child(6) {
                width: auto;
                min-width: 85px;
            }

            #tablaUsuarios th:nth-child(7),
            #tablaUsuarios td:nth-child(7) {
                width: auto;
                min-width: 75px;
            }
        }

    </style>


    {{-- =========================================================
        CONTENIDO
    ========================================================== --}}
    <div class="py-8">

        <div id="contenedorUsuarios"
             class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- =================================================
                MENSAJE DE ESTADO
            ================================================== --}}
            @if (session('status'))

                <div class="mb-6
                            bg-emerald-50 dark:bg-emerald-950/50
                            border border-emerald-200 dark:border-emerald-800
                            text-emerald-700 dark:text-emerald-300
                            px-4 py-3 rounded-xl shadow-sm
                            flex items-center">

                    <svg class="w-5 h-5 mr-2 text-emerald-500 flex-shrink-0"
                         fill="none"
                         stroke="currentColor"
                         viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M5 13l4 4L19 7" />

                    </svg>

                    {{ session('status') }}

                </div>

            @endif


            {{-- =================================================
                TARJETA PRINCIPAL
            ================================================== --}}
            <div id="tarjetaUsuarios"
                 class="bg-white dark:bg-gray-900
                        border border-gray-100 dark:border-gray-800
                        shadow-xl rounded-2xl overflow-hidden">

                <div id="scrollTablaUsuarios">

                    <table id="tablaUsuarios"
                           class="divide-y divide-gray-200 dark:divide-gray-800">

                        {{-- =====================================
                            ENCABEZADO
                        ====================================== --}}
                        <thead class="bg-gray-50/75 dark:bg-gray-800/50
                                      text-gray-500 dark:text-gray-400
                                      text-left text-xs uppercase
                                      font-bold tracking-wider">

                            <tr>

                                <th class="px-6 py-4 whitespace-nowrap">
                                    ID
                                </th>

                                <th class="px-6 py-4 whitespace-nowrap">
                                    Nombre
                                </th>

                                <th class="px-6 py-4 whitespace-nowrap">
                                    Email
                                </th>

                                <th class="px-6 py-4 whitespace-nowrap">
                                    Empresa Asignada
                                </th>

                                <th class="px-6 py-4 whitespace-nowrap">
                                    Rol
                                </th>

                                <th class="px-6 py-4 whitespace-nowrap">
                                    Estatus
                                </th>

                                <th class="px-6 py-4 whitespace-nowrap text-center">
                                    Acciones
                                </th>

                            </tr>

                        </thead>


                        {{-- =====================================
                            CUERPO
                        ====================================== --}}
                        <tbody class="divide-y divide-gray-200 dark:divide-gray-800
                                     bg-white dark:bg-gray-900
                                     text-gray-800 dark:text-gray-200">

                            @forelse ($usuarios as $usuario)

                                <tr class="hover:bg-gray-50/50
                                           dark:hover:bg-gray-800/40
                                           transition-colors text-sm">

                                    {{-- ID --}}
                                    <td class="px-6 py-4 whitespace-nowrap
                                               font-semibold
                                               text-gray-900 dark:text-gray-100">

                                        #{{ $usuario->id }}

                                    </td>


                                    {{-- NOMBRE --}}
                                    <td class="px-6 py-4 whitespace-nowrap font-medium">

                                        <div class="contenido-celda">
                                            {{ $usuario->name }}
                                        </div>

                                    </td>


                                    {{-- EMAIL --}}
                                    <td class="px-6 py-4 whitespace-nowrap
                                               text-gray-500 dark:text-gray-400">

                                        <div class="contenido-celda">
                                            {{ $usuario->email }}
                                        </div>

                                    </td>


                                    {{-- EMPRESA --}}
                                    <td class="px-6 py-4 whitespace-nowrap
                                               text-gray-600 dark:text-gray-300">

                                        <div class="contenido-celda">
                                            {{ $usuario->company->name ?? 'Sin empresa' }}
                                        </div>

                                    </td>


                                    {{-- ROL --}}
                                    <td class="px-6 py-4 whitespace-nowrap">

                                        <span class="badge-usuario
                                                     inline-flex items-center
                                                     px-2.5 py-1
                                                     text-xs font-semibold
                                                     rounded-lg

                                            {{ $usuario->role === 'admin'
                                                ? 'bg-purple-50 text-purple-700 dark:bg-purple-950/50 dark:text-purple-300 border border-purple-200 dark:border-purple-800'
                                                : 'bg-blue-50 text-blue-700 dark:bg-blue-950/50 dark:text-blue-300 border border-blue-200 dark:border-blue-800' }}">

                                            {{ ucfirst($usuario->role) }}

                                        </span>

                                    </td>


                                    {{-- ESTATUS --}}
                                    <td class="px-6 py-4 whitespace-nowrap">

                                        <span class="badge-usuario
                                                     inline-flex items-center
                                                     px-2.5 py-1
                                                     text-xs font-semibold
                                                     rounded-lg

                                            {{ $usuario->status === 'active'
                                                ? 'bg-emerald-50 text-emerald-700 dark:bg-emerald-950/50 dark:text-emerald-300 border border-emerald-200 dark:border-emerald-800'
                                                : 'bg-red-50 text-red-700 dark:bg-red-950/50 dark:text-red-300 border border-red-200 dark:border-red-800' }}">

                                            @if($usuario->status === 'active')

                                                <span class="punto-estatus
                                                             w-1.5 h-1.5
                                                             mr-1.5
                                                             bg-emerald-500
                                                             rounded-full
                                                             animate-pulse">
                                                </span>

                                            @endif

                                            {{ $usuario->status === 'active'
                                                ? 'Activo'
                                                : 'Inactivo' }}

                                        </span>

                                    </td>


                                    {{-- ACCIONES --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">

                                        <a href="{{ route('usuarios.edit', $usuario->id) }}"
                                           class="boton-editar
                                                  inline-flex items-center
                                                  px-3 py-1.5
                                                  bg-gray-100 dark:bg-gray-800
                                                  hover:bg-indigo-50
                                                  dark:hover:bg-indigo-950/50
                                                  text-gray-700 dark:text-gray-300
                                                  hover:text-indigo-600
                                                  dark:hover:text-indigo-400
                                                  text-xs font-semibold
                                                  rounded-lg transition
                                                  border border-gray-200
                                                  dark:border-gray-700">

                                            Editar

                                        </a>

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="7"
                                        class="px-6 py-12
                                               text-center text-sm
                                               text-gray-500
                                               dark:text-gray-400">

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
