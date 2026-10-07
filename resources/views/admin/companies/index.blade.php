<x-app-layout>

    <x-slot name="header">
        <div class="flex justify-between items-center gap-3">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                {{ __('Listado de Empresas') }}
            </h2>

            <a href="{{ route('companies.create') }}"
               class="inline-flex items-center px-4 py-2
                      bg-indigo-600 border border-transparent
                      rounded-md font-semibold text-xs text-white
                      uppercase tracking-widest
                      hover:bg-indigo-500
                      focus:bg-indigo-500
                      active:bg-indigo-700
                      focus:outline-none
                      focus:ring-2 focus:ring-indigo-500
                      focus:ring-offset-2
                      dark:focus:ring-offset-gray-800
                      transition ease-in-out duration-150
                      whitespace-nowrap shrink-0">
                + Nueva Empresa
            </a>
        </div>
    </x-slot>


    {{-- =========================================================
        ESTILOS RESPONSIVOS
    ========================================================== --}}
    <style>

        /*
        ============================================================
        CONTENEDORES
        ============================================================
        */

        #contenedorEmpresas {
            width: 100%;
            min-width: 0;
        }

        #tarjetaEmpresas {
            width: 100%;
            min-width: 0;
            box-sizing: border-box;
        }

        #contenidoEmpresas {
            width: 100%;
            min-width: 0;
            box-sizing: border-box;
        }

        #scrollTablaEmpresas {
            width: 100%;
            min-width: 0;
            overflow-x: auto;
            overflow-y: hidden;
            scrollbar-width: thin;
        }

        #tablaEmpresas {
            width: 100%;
            min-width: 0;
            table-layout: auto;
        }

        #tablaEmpresas th,
        #tablaEmpresas td {
            vertical-align: middle;
        }


        /*
        ============================================================
        PANTALLAS MEDIANAS
        ============================================================
        */

        @media (max-width: 1100px) {

            #contenidoEmpresas {
                padding: 1.15rem;
            }

            #tablaEmpresas th {
                padding-left: 1rem;
                padding-right: 1rem;
                padding-top: 0.7rem;
                padding-bottom: 0.7rem;
                font-size: 0.6875rem;
            }

            #tablaEmpresas td {
                padding-left: 1rem;
                padding-right: 1rem;
                padding-top: 0.75rem;
                padding-bottom: 0.75rem;
            }

            #tablaEmpresas td {
                font-size: 0.8125rem;
            }
        }


        /*
        ============================================================
        VENTANAS MÁS PEQUEÑAS
        ============================================================
        */

        @media (max-width: 900px) {

            #contenidoEmpresas {
                padding: 1rem;
            }

            #tablaEmpresas th {
                padding-left: 0.8rem;
                padding-right: 0.8rem;
                padding-top: 0.6rem;
                padding-bottom: 0.6rem;
                font-size: 0.625rem;
                letter-spacing: 0.04em;
            }

            #tablaEmpresas td {
                padding-left: 0.8rem;
                padding-right: 0.8rem;
                padding-top: 0.65rem;
                padding-bottom: 0.65rem;
                font-size: 0.75rem;
            }

            #tablaEmpresas .boton-editar {
                font-size: 0.75rem;
            }
        }


        /*
        ============================================================
        TABLET / MÓVIL GRANDE
        ============================================================
        */

        @media (max-width: 768px) {

            #contenidoEmpresas {
                padding: 0.85rem;
            }

            #tablaEmpresas th {
                padding-left: 0.65rem;
                padding-right: 0.65rem;
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
                font-size: 0.5625rem;
                line-height: 1.1;
            }

            #tablaEmpresas td {
                padding-left: 0.65rem;
                padding-right: 0.65rem;
                padding-top: 0.55rem;
                padding-bottom: 0.55rem;
                font-size: 0.6875rem;
            }

            #tablaEmpresas .boton-editar {
                font-size: 0.6875rem;
            }
        }


        /*
        ============================================================
        MÓVIL
        ============================================================
        */

        @media (max-width: 640px) {

            #contenidoEmpresas {
                padding: 0.7rem;
            }

            #tablaEmpresas th {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
                padding-top: 0.45rem;
                padding-bottom: 0.45rem;
                font-size: 0.5rem;
                line-height: 1.05;
            }

            #tablaEmpresas td {
                padding-left: 0.5rem;
                padding-right: 0.5rem;
                padding-top: 0.45rem;
                padding-bottom: 0.45rem;
                font-size: 0.625rem;
            }

            #tablaEmpresas .boton-editar {
                font-size: 0.625rem;
            }
        }


        /*
        ============================================================
        MÓVIL PEQUEÑO
        ============================================================
        */

        @media (max-width: 480px) {

            #contenidoEmpresas {
                padding: 0.55rem;
            }

            #tablaEmpresas th {
                padding-left: 0.4rem;
                padding-right: 0.4rem;
                padding-top: 0.4rem;
                padding-bottom: 0.4rem;
                font-size: 0.46875rem;
            }

            #tablaEmpresas td {
                padding-left: 0.4rem;
                padding-right: 0.4rem;
                padding-top: 0.4rem;
                padding-bottom: 0.4rem;
                font-size: 0.5625rem;
            }

            #tablaEmpresas .boton-editar {
                font-size: 0.5625rem;
            }
        }


        /*
        ============================================================
        MÓVIL MUY PEQUEÑO
        ============================================================
        */

        @media (max-width: 380px) {

            #contenidoEmpresas {
                padding: 0.45rem;
            }

            #scrollTablaEmpresas {
                overflow-x: auto;
            }

            #tablaEmpresas {
                min-width: 500px;
            }

            #tablaEmpresas th {
                padding-left: 0.35rem;
                padding-right: 0.35rem;
                padding-top: 0.35rem;
                padding-bottom: 0.35rem;
                font-size: 0.4375rem;
            }

            #tablaEmpresas td {
                padding-left: 0.35rem;
                padding-right: 0.35rem;
                padding-top: 0.35rem;
                padding-bottom: 0.35rem;
                font-size: 0.53125rem;
            }

            #tablaEmpresas .boton-editar {
                font-size: 0.53125rem;
            }
        }


        /*
        ============================================================
        DISTRIBUCIÓN DE COLUMNAS
        ============================================================
        */

        #tablaEmpresas th:nth-child(1),
        #tablaEmpresas td:nth-child(1) {
            width: 45%;
        }

        #tablaEmpresas th:nth-child(2),
        #tablaEmpresas td:nth-child(2) {
            width: 25%;
        }

        #tablaEmpresas th:nth-child(3),
        #tablaEmpresas td:nth-child(3) {
            width: 30%;
        }


        /*
        ============================================================
        EN MÓVILES MUY PEQUEÑOS
        MANTENER TAMAÑOS MÍNIMOS PARA EVITAR QUE SE APLASTE
        ============================================================
        */

        @media (max-width: 380px) {

            #tablaEmpresas th:nth-child(1),
            #tablaEmpresas td:nth-child(1) {
                width: auto;
                min-width: 180px;
            }

            #tablaEmpresas th:nth-child(2),
            #tablaEmpresas td:nth-child(2) {
                width: auto;
                min-width: 120px;
            }

            #tablaEmpresas th:nth-child(3),
            #tablaEmpresas td:nth-child(3) {
                width: auto;
                min-width: 120px;
            }
        }

    </style>


    {{-- =========================================================
        CONTENIDO
    ========================================================== --}}
    <div class="py-12">

        <div id="contenedorEmpresas"
             class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div id="tarjetaEmpresas"
                 class="bg-white dark:bg-gray-800
                        overflow-hidden
                        shadow-sm
                        sm:rounded-lg">

                <div id="contenidoEmpresas"
                     class="p-6 text-gray-900 dark:text-gray-100">

                    {{-- =================================================
                        TABLA DE EMPRESAS
                    ================================================== --}}
                    <div id="scrollTablaEmpresas">

                        <table id="tablaEmpresas"
                               class="min-w-full divide-y
                                      divide-gray-200
                                      dark:divide-gray-700">

                            <thead class="bg-gray-50 dark:bg-gray-700">

                                <tr>

                                    <th scope="col"
                                        class="px-6 py-3
                                               text-left
                                               text-xs
                                               font-medium
                                               text-gray-500
                                               dark:text-gray-300
                                               uppercase
                                               tracking-wider">

                                        Nombre

                                    </th>

                                    <th scope="col"
                                        class="px-6 py-3
                                               text-left
                                               text-xs
                                               font-medium
                                               text-gray-500
                                               dark:text-gray-300
                                               uppercase
                                               tracking-wider">

                                        Usuarios Registrados

                                    </th>

                                    <th scope="col"
                                        class="px-6 py-3
                                               text-right
                                               text-xs
                                               font-medium
                                               text-gray-500
                                               dark:text-gray-300
                                               uppercase
                                               tracking-wider">

                                        Acciones

                                    </th>

                                </tr>

                            </thead>


                            <tbody class="bg-white dark:bg-gray-800
                                           divide-y
                                           divide-gray-200
                                           dark:divide-gray-700">

                                @forelse ($companies as $company)

                                    <tr>

                                        <td class="px-6 py-4
                                                   whitespace-nowrap
                                                   text-sm
                                                   font-medium
                                                   text-gray-900
                                                   dark:text-gray-100">

                                            {{ $company->name }}

                                        </td>

                                        <td class="px-6 py-4
                                                   whitespace-nowrap
                                                   text-sm
                                                   text-gray-500
                                                   dark:text-gray-400">

                                            {{ $company->users_count ?? 0 }}

                                        </td>

                                        <td class="px-6 py-4
                                                   whitespace-nowrap
                                                   text-right
                                                   text-sm
                                                   font-medium">

                                            <a href="{{ route('companies.edit', $company) }}"
                                               class="boton-editar
                                                      text-indigo-600
                                                      dark:text-indigo-400
                                                      hover:text-indigo-900
                                                      mr-3">

                                                Editar

                                            </a>

                                            {{-- Puedes agregar un formulario
                                                 de eliminar aquí si lo requieres --}}

                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="3"
                                            class="px-6 py-4
                                                   text-center
                                                   text-sm
                                                   text-gray-500
                                                   dark:text-gray-400">

                                            No hay empresas registradas todavía.

                                        </td>

                                    </tr>

                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
