<section id="seccionInformacionPerfil">

    {{-- =========================================================
        ESTILOS RESPONSIVOS
    ========================================================== --}}
    <style>

        #seccionInformacionPerfil {
            width: 100%;
            min-width: 0;
            box-sizing: border-box;
        }

        #seccionInformacionPerfil header {
            width: 100%;
            min-width: 0;
        }

        #seccionInformacionPerfil form {
            width: 100%;
            min-width: 0;
            box-sizing: border-box;
        }

        #seccionInformacionPerfil input {
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

            #seccionInformacionPerfil header h2 {
                font-size: 1rem;
            }

            #seccionInformacionPerfil header p {
                font-size: 0.8125rem;
            }

            #seccionInformacionPerfil form {
                margin-top: 1.15rem;
            }

            #seccionInformacionPerfil .campos-perfil {
                gap: 0.8rem;
            }

            #seccionInformacionPerfil input {
                font-size: 0.875rem;
            }
        }


        /*
        ============================================================
        TABLET / VENTANAS PEQUEÑAS
        ============================================================
        */

        @media (max-width: 768px) {

            #seccionInformacionPerfil header h2 {
                font-size: 0.9375rem;
                line-height: 1.25;
            }

            #seccionInformacionPerfil header p {
                margin-top: 0.35rem;
                font-size: 0.75rem;
                line-height: 1.3;
            }

            #seccionInformacionPerfil form {
                margin-top: 0.9rem;
            }

            #seccionInformacionPerfil .campos-perfil {
                gap: 0.65rem;
            }

            #seccionInformacionPerfil label {
                font-size: 0.8125rem;
                line-height: 1.2;
            }

            #seccionInformacionPerfil input {
                margin-top: 0.2rem;
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
                padding-left: 0.7rem;
                padding-right: 0.7rem;
                font-size: 0.8125rem;
                line-height: 1.2;
            }

            #seccionInformacionPerfil .mensaje-verificacion {
                font-size: 0.75rem;
                line-height: 1.3;
            }

            #seccionInformacionPerfil .mensaje-verificacion button {
                font-size: 0.75rem;
            }

            #seccionInformacionPerfil .acciones-perfil {
                margin-top: 0.8rem;
            }

            #seccionInformacionPerfil .acciones-perfil button,
            #seccionInformacionPerfil .acciones-perfil p {
                font-size: 0.75rem;
            }
        }


        /*
        ============================================================
        MÓVIL
        ============================================================
        */

        @media (max-width: 640px) {

            #seccionInformacionPerfil header h2 {
                font-size: 0.875rem;
            }

            #seccionInformacionPerfil header p {
                font-size: 0.6875rem;
                line-height: 1.25;
            }

            #seccionInformacionPerfil form {
                margin-top: 0.7rem;
            }

            #seccionInformacionPerfil .campos-perfil {
                gap: 0.5rem;
            }

            #seccionInformacionPerfil label {
                font-size: 0.75rem;
                line-height: 1.15;
            }

            #seccionInformacionPerfil input {
                margin-top: 0.15rem;
                padding-top: 0.43rem;
                padding-bottom: 0.43rem;
                padding-left: 0.6rem;
                padding-right: 0.6rem;
                font-size: 0.75rem;
                line-height: 1.15;
                border-radius: 0.4rem;
            }

            #seccionInformacionPerfil .mensaje-verificacion {
                margin-top: 0.35rem;
                font-size: 0.6875rem;
                line-height: 1.25;
            }

            #seccionInformacionPerfil .mensaje-verificacion button {
                font-size: 0.6875rem;
            }

            #seccionInformacionPerfil .mensaje-confirmacion {
                margin-top: 0.35rem;
                font-size: 0.6875rem;
            }

            #seccionInformacionPerfil .acciones-perfil {
                margin-top: 0.65rem;
                gap: 0.55rem;
            }

            #seccionInformacionPerfil .acciones-perfil button,
            #seccionInformacionPerfil .acciones-perfil p {
                font-size: 0.6875rem;
            }
        }


        /*
        ============================================================
        MÓVIL PEQUEÑO
        ============================================================
        */

        @media (max-width: 480px) {

            #seccionInformacionPerfil header h2 {
                font-size: 0.8125rem;
            }

            #seccionInformacionPerfil header p {
                font-size: 0.625rem;
                line-height: 1.2;
            }

            #seccionInformacionPerfil form {
                margin-top: 0.55rem;
            }

            #seccionInformacionPerfil .campos-perfil {
                gap: 0.4rem;
            }

            #seccionInformacionPerfil label {
                font-size: 0.6875rem;
                line-height: 1.1;
            }

            #seccionInformacionPerfil input {
                margin-top: 0.1rem;
                padding-top: 0.38rem;
                padding-bottom: 0.38rem;
                padding-left: 0.5rem;
                padding-right: 0.5rem;
                font-size: 0.6875rem;
                line-height: 1.1;
                border-radius: 0.35rem;
            }

            #seccionInformacionPerfil .mensaje-verificacion {
                font-size: 0.625rem;
                line-height: 1.2;
            }

            #seccionInformacionPerfil .mensaje-verificacion button {
                font-size: 0.625rem;
            }

            #seccionInformacionPerfil .mensaje-confirmacion {
                font-size: 0.625rem;
            }

            #seccionInformacionPerfil .acciones-perfil {
                margin-top: 0.5rem;
                gap: 0.45rem;
            }

            #seccionInformacionPerfil .acciones-perfil button,
            #seccionInformacionPerfil .acciones-perfil p {
                font-size: 0.625rem;
            }
        }


        /*
        ============================================================
        MÓVIL MUY PEQUEÑO
        ============================================================
        */

        @media (max-width: 380px) {

            #seccionInformacionPerfil header h2 {
                font-size: 0.75rem;
            }

            #seccionInformacionPerfil header p {
                font-size: 0.5625rem;
            }

            #seccionInformacionPerfil form {
                margin-top: 0.45rem;
            }

            #seccionInformacionPerfil .campos-perfil {
                gap: 0.3rem;
            }

            #seccionInformacionPerfil label {
                font-size: 0.625rem;
            }

            #seccionInformacionPerfil input {
                padding-top: 0.32rem;
                padding-bottom: 0.32rem;
                padding-left: 0.45rem;
                padding-right: 0.45rem;
                font-size: 0.625rem;
            }

            #seccionInformacionPerfil .mensaje-verificacion {
                font-size: 0.5625rem;
            }

            #seccionInformacionPerfil .mensaje-verificacion button {
                font-size: 0.5625rem;
            }

            #seccionInformacionPerfil .mensaje-confirmacion {
                font-size: 0.5625rem;
            }

            #seccionInformacionPerfil .acciones-perfil {
                margin-top: 0.4rem;
                gap: 0.35rem;
            }

            #seccionInformacionPerfil .acciones-perfil button,
            #seccionInformacionPerfil .acciones-perfil p {
                font-size: 0.5625rem;
            }
        }

    </style>


    {{-- =========================================================
        ENCABEZADO
    ========================================================== --}}
    <header>

        <h2 class="text-lg font-medium
                   text-gray-900 dark:text-gray-100">

            {{ __('Informacion de Perfil') }}

        </h2>

        <p class="mt-1 text-sm
                  text-gray-600 dark:text-gray-400">

            {{ __("Update your account's profile information and email address.") }}

        </p>

    </header>


    {{-- =========================================================
        FORMULARIO DE VERIFICACIÓN
    ========================================================== --}}
    <form id="send-verification"
          method="post"
          action="{{ route('verification.send') }}">

        @csrf

    </form>


    {{-- =========================================================
        FORMULARIO PRINCIPAL
    ========================================================== --}}
    <form method="post"
          action="{{ route('profile.update') }}"
          class="mt-6">

        @csrf
        @method('patch')


        {{-- =====================================================
            CAMPOS
        ====================================================== --}}
        <div class="campos-perfil space-y-4">


            {{-- =================================================
                NOMBRE
            ================================================== --}}
            <div>

                <x-input-label
                    for="name"
                    :value="__('Nombre')" />

                <x-text-input
                    id="name"
                    name="name"
                    type="text"
                    class="mt-1 block w-full"
                    :value="old('name', $user->name)"
                    required
                    autofocus
                    autocomplete="name" />

                <x-input-error
                    class="mt-2"
                    :messages="$errors->get('name')" />

            </div>


            {{-- =================================================
                EMAIL
            ================================================== --}}
            <div>

                <x-input-label
                    for="email"
                    :value="__('Email')" />

                <x-text-input
                    id="email"
                    name="email"
                    type="email"
                    class="mt-1 block w-full"
                    :value="old('email', $user->email)"
                    required
                    autocomplete="username" />

                <x-input-error
                    class="mt-2"
                    :messages="$errors->get('email')" />


                {{-- =============================================
                    VERIFICACIÓN DE EMAIL
                ============================================== --}}
                @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail())

                    <div>

                        <p class="mensaje-verificacion
                                  text-sm mt-2
                                  text-gray-800 dark:text-gray-200">

                            {{ __('Your email address is unverified.') }}


                            <button
                                form="send-verification"
                                class="underline text-sm
                                       text-gray-600 dark:text-gray-400
                                       hover:text-gray-900
                                       dark:hover:text-gray-100
                                       rounded-md
                                       focus:outline-none
                                       focus:ring-2
                                       focus:ring-offset-2
                                       focus:ring-indigo-500
                                       dark:focus:ring-offset-gray-800">

                                {{ __('Click here to re-send the verification email.') }}

                            </button>

                        </p>


                        @if (session('status') === 'verification-link-sent')

                            <p class="mensaje-confirmacion
                                      mt-2
                                      font-medium text-sm
                                      text-green-600
                                      dark:text-green-400">

                                {{ __('A new verification link has been sent to your email address.') }}

                            </p>

                        @endif

                    </div>

                @endif

            </div>

        </div>


        {{-- =====================================================
            ACCIONES
        ====================================================== --}}
        <div class="acciones-perfil
                    flex items-center gap-4
                    mt-6">

            <x-primary-button class="whitespace-nowrap">

                {{ __('Save') }}

            </x-primary-button>


            @if (session('status') === 'profile-updated')

                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm
                           text-gray-600
                           dark:text-gray-400
                           whitespace-nowrap"
                >
                    {{ __('Saved.') }}
                </p>

            @endif

        </div>

    </form>

</section>
