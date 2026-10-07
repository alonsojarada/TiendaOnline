
<x-app-layout>

    {{-- =========================================================
        HEADER
    ========================================================== --}}
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Perfil') }}
        </h2>
    </x-slot>


    {{-- =========================================================
        ESTILOS RESPONSIVOS
    ========================================================== --}}
    <style>

        /*
        ============================================================
        CONTENEDOR PRINCIPAL
        ============================================================
        */

        #contenedorPerfil {
            width: 100%;
            min-width: 0;
            box-sizing: border-box;
        }

        #contenedorPerfil .tarjeta-perfil {
            width: 100%;
            min-width: 0;
            box-sizing: border-box;
        }

        #contenedorPerfil .contenido-perfil {
            width: 100%;
            min-width: 0;
            box-sizing: border-box;
        }


        /*
        ============================================================
        PANTALLAS MEDIANAS
        ============================================================
        */

        @media (max-width: 900px) {

            #contenedorPerfil {
                max-width: 42rem;
            }

            #contenedorPerfil .tarjeta-perfil {
                padding: 1.35rem;
            }

            #contenedorPerfil .contenido-perfil {
                max-width: 100%;
            }
        }


        /*
        ============================================================
        TABLET / VENTANAS PEQUEÑAS
        ============================================================
        */

        @media (max-width: 768px) {

            #contenedorPerfil {
                padding-left: 0.65rem;
                padding-right: 0.65rem;
            }

            #contenedorPerfil .tarjeta-perfil {
                padding: 1.15rem;
                border-radius: 0.9rem;
            }

            #contenedorPerfil .contenido-perfil {
                max-width: 100%;
            }
        }


        /*
        ============================================================
        MÓVIL
        ============================================================
        */

        @media (max-width: 640px) {

            #contenedorPerfil {
                padding-left: 0.4rem;
                padding-right: 0.4rem;
            }

            #contenedorPerfil .tarjeta-perfil {
                padding: 0.85rem;
                border-radius: 0.75rem;
            }

            #contenedorPerfil .contenido-perfil {
                max-width: 100%;
            }

            #contenedorPerfil {
                row-gap: 0.75rem;
            }
        }


        /*
        ============================================================
        MÓVIL PEQUEÑO
        ============================================================
        */

        @media (max-width: 480px) {

            #contenedorPerfil {
                padding-left: 0.2rem;
                padding-right: 0.2rem;
                row-gap: 0.6rem;
            }

            #contenedorPerfil .tarjeta-perfil {
                padding: 0.65rem;
                border-radius: 0.65rem;
            }
        }


        /*
        ============================================================
        MÓVIL MUY PEQUEÑO
        ============================================================
        */

        @media (max-width: 380px) {

            #contenedorPerfil {
                padding-left: 0.15rem;
                padding-right: 0.15rem;
                row-gap: 0.5rem;
            }

            #contenedorPerfil .tarjeta-perfil {
                padding: 0.55rem;
                border-radius: 0.55rem;
            }
        }

    </style>


    {{-- =========================================================
        CONTENIDO
    ========================================================== --}}
    <div class="py-12">

        <div id="contenedorPerfil"
             class="max-w-3xl mx-auto sm:px-6 lg:px-8 space-y-6">


            {{-- =================================================
                INFORMACIÓN DEL PERFIL
            ================================================== --}}
            <div class="tarjeta-perfil
                        p-4 sm:p-8
                        bg-white dark:bg-gray-800
                        shadow sm:rounded-lg">

                <div class="contenido-perfil max-w-xl">

                    @include('profile.partials.update-profile-information-form')

                </div>

            </div>


            {{-- =================================================
                CAMBIAR CONTRASEÑA
            ================================================== --}}
            <div class="tarjeta-perfil
                        p-4 sm:p-8
                        bg-white dark:bg-gray-800
                        shadow sm:rounded-lg">

                <div class="contenido-perfil max-w-xl">

                    @include('profile.partials.update-password-form')

                </div>

            </div>


            {{-- =================================================
                ELIMINAR USUARIO
            ================================================== --}}
            <div class="tarjeta-perfil
                        p-4 sm:p-8
                        bg-white dark:bg-gray-800
                        shadow sm:rounded-lg">

                <div class="contenido-perfil max-w-xl">

                    @include('profile.partials.delete-user-form')

                </div>

            </div>

        </div>

    </div>

</x-app-layout>
