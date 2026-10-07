
<x-app-layout>

    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight text-center">
            {{ __('Editar Usuario') }}
        </h2>
    </x-slot>


    {{-- =========================================================
        ESTILOS RESPONSIVOS
    ========================================================== --}}
    <style>

        /*
        ============================================================
        CONFIGURACIÓN BASE
        ============================================================
        */

        #contenedorEditarUsuario {
            width: 100%;
            min-width: 0;
        }

        #tarjetaEditarUsuario {
            width: 100%;
            min-width: 0;
            box-sizing: border-box;
        }

        #formularioEditarUsuario {
            width: 100%;
            box-sizing: border-box;
        }

        #formularioEditarUsuario input,
        #formularioEditarUsuario select {
            box-sizing: border-box;
        }


        /*
        ============================================================
        VENTANAS MEDIANAS
        ============================================================
        */

        @media (max-width: 900px) {

            #contenedorEditarUsuario {
                max-width: 36rem;
            }

            #tarjetaEditarUsuario {
                padding: 1.35rem;
            }

            #formularioEditarUsuario {
                max-width: 33rem;
            }

            #formularioEditarUsuario .campo-usuario {
                margin-top: 0.65rem;
            }

            #formularioEditarUsuario input,
            #formularioEditarUsuario select {
                font-size: 0.875rem;
            }
        }


        /*
        ============================================================
        TABLET / VENTANA PEQUEÑA
        ============================================================
        */

        @media (max-width: 768px) {

            #contenedorEditarUsuario {
                padding-left: 0.65rem;
                padding-right: 0.65rem;
            }

            #tarjetaEditarUsuario {
                padding: 1.15rem;
                border-radius: 0.9rem;
            }

            #formularioEditarUsuario {
                max-width: 100%;
            }

            #formularioEditarUsuario .campo-usuario {
                margin-top: 0.55rem;
            }

            #formularioEditarUsuario input,
            #formularioEditarUsuario select {
                margin-top: 0.2rem;
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
                padding-left: 0.7rem;
                padding-right: 0.7rem;
                font-size: 0.8125rem;
                line-height: 1.2;
            }

            #formularioEditarUsuario label {
                font-size: 0.8125rem;
                line-height: 1.2;
            }

            #formularioEditarUsuario .acciones-formulario {
                margin-top: 0.75rem;
            }

            #formularioEditarUsuario .acciones-formulario a,
            #formularioEditarUsuario .acciones-formulario button {
                font-size: 0.75rem;
            }
        }


        /*
        ============================================================
        MÓVIL
        ============================================================
        */

        @media (max-width: 640px) {

            #contenedorEditarUsuario {
                padding-left: 0.4rem;
                padding-right: 0.4rem;
            }

            #tarjetaEditarUsuario {
                padding: 0.85rem;
                border-radius: 0.75rem;
            }

            #formularioEditarUsuario {
                max-width: 100%;
            }

            #formularioEditarUsuario .campo-usuario {
                margin-top: 0.45rem;
            }

            #formularioEditarUsuario input,
            #formularioEditarUsuario select {
                margin-top: 0.15rem;
                padding-top: 0.43rem;
                padding-bottom: 0.43rem;
                padding-left: 0.6rem;
                padding-right: 0.6rem;
                font-size: 0.75rem;
                line-height: 1.15;
                border-radius: 0.4rem;
            }

            #formularioEditarUsuario label {
                font-size: 0.75rem;
                line-height: 1.15;
            }

            #formularioEditarUsuario .acciones-formulario {
                margin-top: 0.65rem;
            }

            #formularioEditarUsuario .acciones-formulario a,
            #formularioEditarUsuario .acciones-formulario button {
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

            #contenedorEditarUsuario {
                padding-left: 0.2rem;
                padding-right: 0.2rem;
            }

            #tarjetaEditarUsuario {
                padding: 0.65rem;
                border-radius: 0.65rem;
            }

            #formularioEditarUsuario {
                gap: 0.45rem;
            }

            #formularioEditarUsuario .campo-usuario {
                margin-top: 0.35rem;
            }

            #formularioEditarUsuario input,
            #formularioEditarUsuario select {
                margin-top: 0.1rem;
                padding-top: 0.38rem;
                padding-bottom: 0.38rem;
                padding-left: 0.5rem;
                padding-right: 0.5rem;
                font-size: 0.6875rem;
                line-height: 1.1;
                border-radius: 0.35rem;
            }

            #formularioEditarUsuario label {
                font-size: 0.6875rem;
                line-height: 1.1;
            }

            #formularioEditarUsuario .acciones-formulario {
                margin-top: 0.5rem;
            }

            #formularioEditarUsuario .acciones-formulario a,
            #formularioEditarUsuario .acciones-formulario button {
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

            #contenedorEditarUsuario {
                padding-left: 0.15rem;
                padding-right: 0.15rem;
            }

            #tarjetaEditarUsuario {
                padding: 0.55rem;
            }

            #formularioEditarUsuario {
                gap: 0.35rem;
            }

            #formularioEditarUsuario .campo-usuario {
                margin-top: 0.25rem;
            }

            #formularioEditarUsuario input,
            #formularioEditarUsuario select {
                padding-top: 0.32rem;
                padding-bottom: 0.32rem;
                padding-left: 0.45rem;
                padding-right: 0.45rem;
                font-size: 0.625rem;
            }

            #formularioEditarUsuario label {
                font-size: 0.625rem;
            }

            #formularioEditarUsuario .acciones-formulario {
                margin-top: 0.4rem;
            }

            #formularioEditarUsuario .acciones-formulario a,
            #formularioEditarUsuario .acciones-formulario button {
                font-size: 0.5625rem;
            }
        }


        /*
        ============================================================
        EVITAR DESBORDAMIENTO EN ACCIONES
        ============================================================
        */

        #formularioEditarUsuario .acciones-formulario {
            min-width: 0;
        }

        #formularioEditarUsuario .acciones-formulario a,
        #formularioEditarUsuario .acciones-formulario button {
            white-space: nowrap;
            flex-shrink: 1;
        }

    </style>


    {{-- =========================================================
        CONTENIDO
    ========================================================== --}}
    <div class="py-12">

        <div id="contenedorEditarUsuario"
             class="max-w-xl mx-auto sm:px-6 lg:px-8">

            <div id="tarjetaEditarUsuario"
                 class="bg-white dark:bg-gray-800
                        overflow-hidden
                        shadow-sm
                        sm:rounded-lg
                        p-6">

                <form id="formularioEditarUsuario"
                      method="POST"
                      action="{{ route('usuarios.update', $user->id) }}"
                      class="max-w-md mx-auto space-y-4">

                    @csrf
                    @method('PUT')


                    {{-- =================================================
                        NOMBRE
                    ================================================== --}}
                    <div class="campo-usuario">

                        <x-input-label
                            for="name"
                            :value="__('Nombre')" />

                        <x-text-input
                            id="name"
                            class="block mt-1 w-full"
                            type="text"
                            name="name"
                            :value="old('name', $user->name)"
                            required
                            autofocus />

                        <x-input-error
                            :messages="$errors->get('name')"
                            class="mt-2" />

                    </div>


                    {{-- =================================================
                        EMAIL
                    ================================================== --}}
                    <div class="campo-usuario">

                        <x-input-label
                            for="email"
                            :value="__('Correo Electrónico')" />

                        <x-text-input
                            id="email"
                            class="block mt-1 w-full"
                            type="email"
                            name="email"
                            :value="old('email', $user->email)"
                            required />

                        <x-input-error
                            :messages="$errors->get('email')"
                            class="mt-2" />

                    </div>


                    {{-- =================================================
                        EMPRESA ASIGNADA
                    ================================================== --}}
                    <div class="campo-usuario">

                        <x-input-label
                            for="company_id"
                            :value="__('Empresa Asignada')" />

                        <select
                            id="company_id"
                            name="company_id"
                            required
                            class="block mt-1 w-full
                                   border-gray-300
                                   dark:border-gray-700
                                   dark:bg-gray-900
                                   dark:text-gray-300
                                   focus:border-indigo-500
                                   dark:focus:border-indigo-600
                                   focus:ring-indigo-500
                                   dark:focus:ring-indigo-600
                                   rounded-md
                                   shadow-sm">

                            <option value="">
                                Seleccione una empresa...
                            </option>

                            @foreach($companies as $company)

                                <option
                                    value="{{ $company->id }}"
                                    {{ old('company_id', $user->company_id) == $company->id ? 'selected' : '' }}>

                                    {{ $company->name }}

                                </option>

                            @endforeach

                        </select>

                        <x-input-error
                            :messages="$errors->get('company_id')"
                            class="mt-2" />

                    </div>


                    {{-- =================================================
                        ROL DEL USUARIO
                    ================================================== --}}
                    <div class="campo-usuario">

                        <x-input-label
                            for="role"
                            :value="__('Rol del Usuario')" />

                        <select
                            id="role"
                            name="role"
                            class="block mt-1 w-full
                                   border-gray-300
                                   dark:border-gray-700
                                   dark:bg-gray-900
                                   dark:text-gray-300
                                   focus:border-indigo-500
                                   dark:focus:border-indigo-600
                                   focus:ring-indigo-500
                                   dark:focus:ring-indigo-600
                                   rounded-md
                                   shadow-sm">

                            {{-- 
                            <option value="admin"
                                {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                Administrador
                            </option>
                            --}}

                            <option
                                value="seller"
                                {{ old('role', $user->role) == 'seller' ? 'selected' : '' }}>

                                Vendedor / Encargado

                            </option>

                            <option
                                value="cashier"
                                {{ old('role', $user->role) == 'cashier' ? 'selected' : '' }}>

                                Cajero

                            </option>

                        </select>

                        <x-input-error
                            :messages="$errors->get('role')"
                            class="mt-2" />

                    </div>


                    {{-- =================================================
                        ESTATUS
                    ================================================== --}}
                    <div class="campo-usuario">

                        <x-input-label
                            for="status"
                            :value="__('Estatus')" />

                        <select
                            id="status"
                            name="status"
                            required
                            class="block mt-1 w-full
                                   border-gray-300
                                   dark:border-gray-700
                                   dark:bg-gray-900
                                   dark:text-gray-300
                                   focus:border-indigo-500
                                   dark:focus:border-indigo-600
                                   focus:ring-indigo-500
                                   dark:focus:ring-indigo-600
                                   rounded-md
                                   shadow-sm">

                            <option
                                value="active"
                                {{ old('status', $user->status ?? 'active') == 'active' ? 'selected' : '' }}>

                                Activo

                            </option>

                            <option
                                value="inactive"
                                {{ old('status', $user->status ?? '') == 'inactive' ? 'selected' : '' }}>

                                Inactivo

                            </option>

                        </select>

                        <x-input-error
                            :messages="$errors->get('status')"
                            class="mt-2" />

                    </div>


                    {{-- =================================================
                        NUEVA CONTRASEÑA
                    ================================================== --}}
                    <div class="campo-usuario">

                        <x-input-label
                            for="password"
                            :value="__('Nueva Contraseña (Opcional)')" />

                        <x-text-input
                            id="password"
                            class="block mt-1 w-full"
                            type="password"
                            name="password" />

                        <x-input-error
                            :messages="$errors->get('password')"
                            class="mt-2" />

                    </div>


                    {{-- =================================================
                        CONFIRMAR CONTRASEÑA
                    ================================================== --}}
                    <div class="campo-usuario">

                        <x-input-label
                            for="password_confirmation"
                            :value="__('Confirmar Nueva Contraseña')" />

                        <x-text-input
                            id="password_confirmation"
                            class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" />

                        <x-input-error
                            :messages="$errors->get('password_confirmation')"
                            class="mt-2" />

                    </div>


                    {{-- =================================================
                        ACCIONES
                    ================================================== --}}
                    <div class="acciones-formulario
                                flex items-center justify-between
                                mt-4">

                        <a href="{{ route('usuarios.index') }}"
                           class="text-sm
                                  text-gray-600 dark:text-gray-400
                                  hover:text-gray-900
                                  dark:hover:text-gray-100
                                  underline
                                  whitespace-nowrap">

                            {{ __('Cancelar') }}

                        </a>

                        <x-primary-button class="whitespace-nowrap">

                            {{ __('Actualizar Usuario') }}

                        </x-primary-button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>
