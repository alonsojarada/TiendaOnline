
<section id="seccionActualizarPassword">

    {{-- =========================================================
        ESTILOS RESPONSIVOS
    ========================================================== --}}
    <style>

        #seccionActualizarPassword {
            width: 100%;
            min-width: 0;
            box-sizing: border-box;
        }

        #seccionActualizarPassword header {
            width: 100%;
            min-width: 0;
        }

        #seccionActualizarPassword form {
            width: 100%;
            min-width: 0;
            box-sizing: border-box;
        }

        #seccionActualizarPassword input {
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

            #seccionActualizarPassword header h2 {
                font-size: 1rem;
            }

            #seccionActualizarPassword header p {
                font-size: 0.8125rem;
            }

            #seccionActualizarPassword form {
                margin-top: 1.15rem;
            }

            #seccionActualizarPassword form > div {
                margin-top: 0.85rem;
            }

            #seccionActualizarPassword input {
                font-size: 0.875rem;
            }
        }


        /*
        ============================================================
        TABLET / VENTANAS PEQUEÑAS
        ============================================================
        */

        @media (max-width: 768px) {

            #seccionActualizarPassword header h2 {
                font-size: 0.9375rem;
                line-height: 1.25;
            }

            #seccionActualizarPassword header p {
                margin-top: 0.35rem;
                font-size: 0.75rem;
                line-height: 1.3;
            }

            #seccionActualizarPassword form {
                margin-top: 0.9rem;
            }

            #seccionActualizarPassword form > div {
                margin-top: 0.7rem;
            }

            #seccionActualizarPassword form > div:first-of-type {
                margin-top: 0;
            }

            #seccionActualizarPassword label {
                font-size: 0.8125rem;
                line-height: 1.2;
            }

            #seccionActualizarPassword input {
                margin-top: 0.2rem;
                padding-top: 0.5rem;
                padding-bottom: 0.5rem;
                padding-left: 0.7rem;
                padding-right: 0.7rem;
                font-size: 0.8125rem;
                line-height: 1.2;
            }

            #seccionActualizarPassword .acciones-password {
                margin-top: 0.8rem;
            }

            #seccionActualizarPassword .acciones-password button,
            #seccionActualizarPassword .acciones-password p {
                font-size: 0.75rem;
            }
        }


        /*
        ============================================================
        MÓVIL
        ============================================================
        */

        @media (max-width: 640px) {

            #seccionActualizarPassword header h2 {
                font-size: 0.875rem;
            }

            #seccionActualizarPassword header p {
                font-size: 0.6875rem;
                line-height: 1.25;
            }

            #seccionActualizarPassword form {
                margin-top: 0.7rem;
            }

            #seccionActualizarPassword form > div {
                margin-top: 0.55rem;
            }

            #seccionActualizarPassword label {
                font-size: 0.75rem;
                line-height: 1.15;
            }

            #seccionActualizarPassword input {
                margin-top: 0.15rem;
                padding-top: 0.43rem;
                padding-bottom: 0.43rem;
                padding-left: 0.6rem;
                padding-right: 0.6rem;
                font-size: 0.75rem;
                line-height: 1.15;
                border-radius: 0.4rem;
            }

            #seccionActualizarPassword .acciones-password {
                margin-top: 0.65rem;
                gap: 0.55rem;
            }

            #seccionActualizarPassword .acciones-password button,
            #seccionActualizarPassword .acciones-password p {
                font-size: 0.6875rem;
            }
        }


        /*
        ============================================================
        MÓVIL PEQUEÑO
        ============================================================
        */

        @media (max-width: 480px) {

            #seccionActualizarPassword header h2 {
                font-size: 0.8125rem;
            }

            #seccionActualizarPassword header p {
                font-size: 0.625rem;
                line-height: 1.2;
            }

            #seccionActualizarPassword form {
                margin-top: 0.55rem;
            }

            #seccionActualizarPassword form > div {
                margin-top: 0.4rem;
            }

            #seccionActualizarPassword label {
                font-size: 0.6875rem;
                line-height: 1.1;
            }

            #seccionActualizarPassword input {
                margin-top: 0.1rem;
                padding-top: 0.38rem;
                padding-bottom: 0.38rem;
                padding-left: 0.5rem;
                padding-right: 0.5rem;
                font-size: 0.6875rem;
                line-height: 1.1;
                border-radius: 0.35rem;
            }

            #seccionActualizarPassword .acciones-password {
                margin-top: 0.5rem;
                gap: 0.45rem;
            }

            #seccionActualizarPassword .acciones-password button,
            #seccionActualizarPassword .acciones-password p {
                font-size: 0.625rem;
            }
        }


        /*
        ============================================================
        MÓVIL MUY PEQUEÑO
        ============================================================
        */

        @media (max-width: 380px) {

            #seccionActualizarPassword header h2 {
                font-size: 0.75rem;
            }

            #seccionActualizarPassword header p {
                font-size: 0.5625rem;
            }

            #seccionActualizarPassword form {
                margin-top: 0.45rem;
            }

            #seccionActualizarPassword form > div {
                margin-top: 0.3rem;
            }

            #seccionActualizarPassword label {
                font-size: 0.625rem;
            }

            #seccionActualizarPassword input {
                padding-top: 0.32rem;
                padding-bottom: 0.32rem;
                padding-left: 0.45rem;
                padding-right: 0.45rem;
                font-size: 0.625rem;
            }

            #seccionActualizarPassword .acciones-password {
                margin-top: 0.4rem;
                gap: 0.35rem;
            }

            #seccionActualizarPassword .acciones-password button,
            #seccionActualizarPassword .acciones-password p {
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

            {{ __('Actualizar Password') }}

        </h2>

        <p class="mt-1 text-sm
                  text-gray-600 dark:text-gray-400">

            {{ __('Ensure your account is using a long, random password to stay secure.') }}

        </p>

    </header>


    {{-- =========================================================
        FORMULARIO
    ========================================================== --}}
    <form method="post"
          action="{{ route('password.update') }}"
          class="mt-6 space-y-6">

        @csrf
        @method('put')


        {{-- =====================================================
            PASSWORD ACTUAL
        ====================================================== --}}
        <div>

            <x-input-label
                for="update_password_current_password"
                :value="__('Actual Password')" />

            <x-text-input
                id="update_password_current_password"
                name="current_password"
                type="password"
                class="mt-1 block w-full"
                autocomplete="current-password" />

            <x-input-error
                :messages="$errors->updatePassword->get('current_password')"
                class="mt-2" />

        </div>


        {{-- =====================================================
            NUEVO PASSWORD
        ====================================================== --}}
        <div>

            <x-input-label
                for="update_password_password"
                :value="__('Nuevo Password')" />

            <x-text-input
                id="update_password_password"
                name="password"
                type="password"
                class="mt-1 block w-full"
                autocomplete="new-password" />

            <x-input-error
                :messages="$errors->updatePassword->get('password')"
                class="mt-2" />

        </div>


        {{-- =====================================================
            CONFIRMAR PASSWORD
        ====================================================== --}}
        <div>

            <x-input-label
                for="update_password_password_confirmation"
                :value="__('Confirmar Nuevo Password')" />

            <x-text-input
                id="update_password_password_confirmation"
                name="password_confirmation"
                type="password"
                class="mt-1 block w-full"
                autocomplete="new-password" />

            <x-input-error
                :messages="$errors->updatePassword->get('password_confirmation')"
                class="mt-2" />

        </div>


        {{-- =====================================================
            ACCIONES
        ====================================================== --}}
        <div class="acciones-password flex items-center gap-4">

            <x-primary-button class="whitespace-nowrap">
                {{ __('Save') }}
            </x-primary-button>


            @if (session('status') === 'password-updated')

                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600
                           dark:text-gray-400
                           whitespace-nowrap"
                >
                    {{ __('Saved.') }}
                </p>

            @endif

        </div>

    </form>

</section>
