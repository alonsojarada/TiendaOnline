
<x-app-layout>

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('Registrar Nueva Empresa') }}
        </h2>
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

        #contenedorNuevaEmpresa {
            width: 100%;
            min-width: 0;
        }

        #tarjetaNuevaEmpresa {
            width: 100%;
            min-width: 0;
            box-sizing: border-box;
        }

        #formularioNuevaEmpresa {
            width: 100%;
            box-sizing: border-box;
        }

        #formularioNuevaEmpresa input {
            box-sizing: border-box;
        }


        /*
        ============================================================
        PANTALLAS MEDIANAS
        ============================================================
        */

        @media (max-width: 900px) {

            #contenedorNuevaEmpresa {
                max-width: 36rem;
            }

            #tarjetaNuevaEmpresa {
                padding: 1.35rem;
            }

            #formularioNuevaEmpresa {
                max-width: 33rem;
            }

            #formularioNuevaEmpresa .campo-empresa {
                margin-top: 0.65rem;
            }

            #formularioNuevaEmpresa input {
                font-size: 0.875rem;
            }
        }


        /*
        ============================================================
        TABLET / VENTANAS PEQUEÑAS
        ============================================================
        */

        @media (max-width: 768px) {

            #contenedorNuevaEmpresa {
                padding-left: 0.65rem;
                padding-right: 0.65rem;
            }

            #tarjetaNuevaEmpresa {
                padding: 1.15rem;
                border-radius: 0.9rem;
            }

            #formularioNuevaEmpresa {
                max-width: 100%;
            }

            #formularioNuevaEmpresa .campo-empresa {
                margin-top: 0.55rem;
            }

            #formularioNuevaEmpresa input {
                margin-top: 0.2rem;
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
                padding-left: 0.7rem;
                padding-right: 0.7rem;
                font-size: 0.8125rem;
                line-height: 1.2;
            }

            #formularioNuevaEmpresa label {
                font-size: 0.8125rem;
                line-height: 1.2;
            }

            #formularioNuevaEmpresa .acciones-formulario {
                margin-top: 0.75rem;
            }

            #formularioNuevaEmpresa .acciones-formulario a,
            #formularioNuevaEmpresa .acciones-formulario button {
                font-size: 0.75rem;
            }
        }


        /*
        ============================================================
        MÓVIL
        ============================================================
        */

        @media (max-width: 640px) {

            #contenedorNuevaEmpresa {
                padding-left: 0.4rem;
                padding-right: 0.4rem;
            }

            #tarjetaNuevaEmpresa {
                padding: 0.85rem;
                border-radius: 0.75rem;
            }

            #formularioNuevaEmpresa {
                max-width: 100%;
            }

            #formularioNuevaEmpresa .campo-empresa {
                margin-top: 0.45rem;
            }

            #formularioNuevaEmpresa input {
                margin-top: 0.15rem;
                padding-top: 0.43rem;
                padding-bottom: 0.43rem;
                padding-left: 0.6rem;
                padding-right: 0.6rem;
                font-size: 0.75rem;
                line-height: 1.15;
                border-radius: 0.4rem;
            }

            #formularioNuevaEmpresa label {
                font-size: 0.75rem;
                line-height: 1.15;
            }

            #formularioNuevaEmpresa .acciones-formulario {
                margin-top: 0.65rem;
            }

            #formularioNuevaEmpresa .acciones-formulario a,
            #formularioNuevaEmpresa .acciones-formulario button {
                font-size: 0.6875rem;
                line-height: 1;
            }
        }


        /*
        ============================================================
        MÓVIL PEQUEÑO
        ============================================================
        */

        @media (max-width: 480px) {

            #contenedorNuevaEmpresa {
                padding-left: 0.2rem;
                padding-right: 0.2rem;
            }

            #tarjetaNuevaEmpresa {
                padding: 0.65rem;
                border-radius: 0.65rem;
            }

            #formularioNuevaEmpresa {
                gap: 0.45rem;
            }

            #formularioNuevaEmpresa .campo-empresa {
                margin-top: 0.35rem;
            }

            #formularioNuevaEmpresa input {
                margin-top: 0.1rem;
                padding-top: 0.38rem;
                padding-bottom: 0.38rem;
                padding-left: 0.5rem;
                padding-right: 0.5rem;
                font-size: 0.6875rem;
                line-height: 1.1;
                border-radius: 0.35rem;
            }

            #formularioNuevaEmpresa label {
                font-size: 0.6875rem;
                line-height: 1.1;
            }

            #formularioNuevaEmpresa .acciones-formulario {
                margin-top: 0.5rem;
            }

            #formularioNuevaEmpresa .acciones-formulario a,
            #formularioNuevaEmpresa .acciones-formulario button {
                font-size: 0.625rem;
                line-height: 1;
            }
        }


        /*
        ============================================================
        MÓVIL MUY PEQUEÑO
        ============================================================
        */

        @media (max-width: 380px) {

            #contenedorNuevaEmpresa {
                padding-left: 0.15rem;
                padding-right: 0.15rem;
            }

            #tarjetaNuevaEmpresa {
                padding: 0.55rem;
            }

            #formularioNuevaEmpresa {
                gap: 0.35rem;
            }

            #formularioNuevaEmpresa .campo-empresa {
                margin-top: 0.25rem;
            }

            #formularioNuevaEmpresa input {
                padding-top: 0.32rem;
                padding-bottom: 0.32rem;
                padding-left: 0.45rem;
                padding-right: 0.45rem;
                font-size: 0.625rem;
            }

            #formularioNuevaEmpresa label {
                font-size: 0.625rem;
            }

            #formularioNuevaEmpresa .acciones-formulario {
                margin-top: 0.4rem;
            }

            #formularioNuevaEmpresa .acciones-formulario a,
            #formularioNuevaEmpresa .acciones-formulario button {
                font-size: 0.5625rem;
            }
        }


        /*
        ============================================================
        ACCIONES
        ============================================================
        */

        #formularioNuevaEmpresa .acciones-formulario {
            min-width: 0;
        }

        #formularioNuevaEmpresa .acciones-formulario a,
        #formularioNuevaEmpresa .acciones-formulario button {
            white-space: nowrap;
            flex-shrink: 1;
        }

    </style>


    {{-- =========================================================
        CONTENIDO
    ========================================================== --}}
    <div class="py-12">

        <div id="contenedorNuevaEmpresa"
             class="max-w-xl mx-auto sm:px-6 lg:px-8">

            <div id="tarjetaNuevaEmpresa"
                 class="bg-white dark:bg-gray-800
                        overflow-hidden
                        shadow-sm
                        sm:rounded-lg
                        p-6">

                <form id="formularioNuevaEmpresa"
                      method="POST"
                      action="{{ route('companies.store') }}"
                      class="max-w-md mx-auto space-y-4">

                    @csrf


                    {{-- =================================================
                        NOMBRE
                    ================================================== --}}
                    <div class="campo-empresa">

                        <x-input-label
                            for="name"
                            :value="__('Nombre de la Empresa')" />

                        <x-text-input
                            id="name"
                            class="block mt-1 w-full"
                            type="text"
                            name="name"
                            :value="old('name')"
                            required
                            autofocus />

                        <x-input-error
                            :messages="$errors->get('name')"
                            class="mt-2" />

                    </div>


                    {{-- =================================================
                        SLUG
                    ================================================== --}}
                    <div class="campo-empresa">

                        <x-input-label
                            for="slug"
                            :value="__('Slug (Identificador único ej. mi-tienda)')" />

                        <x-text-input
                            id="slug"
                            class="block mt-1 w-full"
                            type="text"
                            name="slug"
                            :value="old('slug')"
                            required />

                        <x-input-error
                            :messages="$errors->get('slug')"
                            class="mt-2" />

                    </div>


                    {{-- =================================================
                        TELÉFONO
                    ================================================== --}}
                    <div class="campo-empresa">

                        <x-input-label
                            for="phone"
                            :value="__('Teléfono (Opcional)')" />

                        <x-text-input
                            id="phone"
                            class="block mt-1 w-full"
                            type="text"
                            name="phone"
                            :value="old('phone')" />

                        <x-input-error
                            :messages="$errors->get('phone')"
                            class="mt-2" />

                    </div>


                    {{-- =================================================
                        DIRECCIÓN
                    ================================================== --}}
                    <div class="campo-empresa">

                        <x-input-label
                            for="address"
                            :value="__('Dirección (Opcional)')" />

                        <x-text-input
                            id="address"
                            class="block mt-1 w-full"
                            type="text"
                            name="address"
                            :value="old('address')" />

                        <x-input-error
                            :messages="$errors->get('address')"
                            class="mt-2" />

                    </div>


                    {{-- =================================================
                        ACCIONES
                    ================================================== --}}
                    <div class="acciones-formulario
                                flex items-center justify-between
                                mt-4">

                        <a href="{{ route('companies.index') }}"
                           class="text-sm
                                  text-gray-600
                                  dark:text-gray-400
                                  underline
                                  whitespace-nowrap">

                            {{ __('Cancelar') }}

                        </a>


                        <x-primary-button class="whitespace-nowrap">

                            {{ __('Guardar Empresa') }}

                        </x-primary-button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>
