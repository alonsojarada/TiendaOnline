
<section id="seccionEliminarUsuario" class="space-y-6">

    <style>
        /* =========================================================
           ELIMINAR CUENTA - RESPONSIVE
        ========================================================== */

        #seccionEliminarUsuario,
        #seccionEliminarUsuario * {
            box-sizing: border-box;
        }

        #seccionEliminarUsuario {
            min-width: 0;
        }

        #seccionEliminarUsuario header {
            min-width: 0;
        }

        #seccionEliminarUsuario h2 {
            line-height: 1.3;
        }

        #seccionEliminarUsuario .descripcion-eliminar {
            line-height: 1.5;
        }

        #seccionEliminarUsuario .boton-eliminar-principal {
            white-space: nowrap;
            flex-shrink: 0;
        }

        #seccionEliminarUsuario .modal-eliminar {
            min-width: 0;
        }

        #seccionEliminarUsuario .contenido-modal-eliminar {
            min-width: 0;
        }

        #seccionEliminarUsuario .acciones-modal-eliminar {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: nowrap;
        }

        #seccionEliminarUsuario .acciones-modal-eliminar button {
            white-space: nowrap;
            flex-shrink: 0;
        }

        /* =========================================================
           VENTANAS MEDIANAS
        ========================================================== */
        @media (max-width: 900px) {

            #seccionEliminarUsuario {
                gap: 1.25rem;
            }

            #seccionEliminarUsuario h2 {
                font-size: 1rem;
            }

            #seccionEliminarUsuario .descripcion-eliminar {
                font-size: 0.875rem;
                line-height: 1.45;
            }

            #seccionEliminarUsuario .boton-eliminar-principal {
                font-size: 0.875rem;
                padding-top: 0.55rem;
                padding-bottom: 0.55rem;
            }

            #seccionEliminarUsuario .contenido-modal-eliminar {
                padding: 1.25rem;
            }

            #seccionEliminarUsuario .contenido-modal-eliminar h2 {
                font-size: 1rem;
            }

            #seccionEliminarUsuario .contenido-modal-eliminar p {
                font-size: 0.875rem;
                line-height: 1.45;
            }

            #seccionEliminarUsuario .password-eliminar {
                width: 75%;
                font-size: 0.875rem;
            }
        }

        /* =========================================================
           VENTANAS CHICAS
        ========================================================== */
        @media (max-width: 768px) {

            #seccionEliminarUsuario {
                gap: 1rem;
            }

            #seccionEliminarUsuario h2 {
                font-size: 0.95rem;
            }

            #seccionEliminarUsuario .descripcion-eliminar {
                margin-top: 0.25rem;
                font-size: 0.8rem;
                line-height: 1.4;
            }

            #seccionEliminarUsuario .boton-eliminar-principal {
                font-size: 0.8rem;
                padding: 0.5rem 0.8rem;
            }

            #seccionEliminarUsuario .contenido-modal-eliminar {
                padding: 1rem;
            }

            #seccionEliminarUsuario .contenido-modal-eliminar h2 {
                font-size: 0.95rem;
            }

            #seccionEliminarUsuario .contenido-modal-eliminar p {
                font-size: 0.8rem;
                line-height: 1.4;
            }

            #seccionEliminarUsuario .password-eliminar {
                width: 100%;
                font-size: 0.8rem;
            }

            #seccionEliminarUsuario .acciones-modal-eliminar {
                gap: 0.5rem;
            }

            #seccionEliminarUsuario .acciones-modal-eliminar button {
                font-size: 0.8rem;
                padding: 0.45rem 0.7rem;
            }
        }

        /* =========================================================
           MÓVILES
        ========================================================== */
        @media (max-width: 640px) {

            #seccionEliminarUsuario {
                gap: 0.85rem;
            }

            #seccionEliminarUsuario h2 {
                font-size: 0.9rem;
            }

            #seccionEliminarUsuario .descripcion-eliminar {
                font-size: 0.75rem;
                line-height: 1.35;
            }

            #seccionEliminarUsuario .boton-eliminar-principal {
                font-size: 0.75rem;
                padding: 0.45rem 0.7rem;
            }

            #seccionEliminarUsuario .contenido-modal-eliminar {
                padding: 0.9rem;
            }

            #seccionEliminarUsuario .contenido-modal-eliminar h2 {
                font-size: 0.9rem;
                line-height: 1.3;
            }

            #seccionEliminarUsuario .contenido-modal-eliminar p {
                font-size: 0.75rem;
                line-height: 1.35;
            }

            #seccionEliminarUsuario .password-eliminar {
                margin-top: 0.5rem;
                width: 100%;
                font-size: 0.75rem;
                padding: 0.5rem 0.65rem;
            }

            #seccionEliminarUsuario .acciones-modal-eliminar {
                margin-top: 1rem;
                gap: 0.4rem;
            }

            #seccionEliminarUsuario .acciones-modal-eliminar button {
                font-size: 0.75rem;
                padding: 0.4rem 0.6rem;
            }
        }

        /* =========================================================
           MÓVILES PEQUEÑOS
        ========================================================== */
        @media (max-width: 480px) {

            #seccionEliminarUsuario {
                gap: 0.7rem;
            }

            #seccionEliminarUsuario h2 {
                font-size: 0.85rem;
            }

            #seccionEliminarUsuario .descripcion-eliminar {
                font-size: 0.7rem;
                line-height: 1.3;
            }

            #seccionEliminarUsuario .boton-eliminar-principal {
                font-size: 0.7rem;
                padding: 0.4rem 0.6rem;
            }

            #seccionEliminarUsuario .contenido-modal-eliminar {
                padding: 0.8rem;
            }

            #seccionEliminarUsuario .contenido-modal-eliminar h2 {
                font-size: 0.85rem;
            }

            #seccionEliminarUsuario .contenido-modal-eliminar p {
                font-size: 0.7rem;
                line-height: 1.3;
            }

            #seccionEliminarUsuario .password-eliminar {
                font-size: 0.7rem;
                padding: 0.45rem 0.6rem;
            }

            #seccionEliminarUsuario .acciones-modal-eliminar {
                gap: 0.35rem;
            }

            #seccionEliminarUsuario .acciones-modal-eliminar button {
                font-size: 0.7rem;
                padding: 0.35rem 0.55rem;
            }
        }

        /* =========================================================
           MÓVILES MUY PEQUEÑOS
        ========================================================== */
        @media (max-width: 380px) {

            #seccionEliminarUsuario {
                gap: 0.6rem;
            }

            #seccionEliminarUsuario h2 {
                font-size: 0.8rem;
            }

            #seccionEliminarUsuario .descripcion-eliminar {
                font-size: 0.65rem;
                line-height: 1.3;
            }

            #seccionEliminarUsuario .boton-eliminar-principal {
                font-size: 0.65rem;
                padding: 0.35rem 0.5rem;
            }

            #seccionEliminarUsuario .contenido-modal-eliminar {
                padding: 0.7rem;
            }

            #seccionEliminarUsuario .contenido-modal-eliminar h2 {
                font-size: 0.8rem;
            }

            #seccionEliminarUsuario .contenido-modal-eliminar p {
                font-size: 0.65rem;
                line-height: 1.3;
            }

            #seccionEliminarUsuario .password-eliminar {
                font-size: 0.65rem;
                padding: 0.4rem 0.5rem;
            }

            #seccionEliminarUsuario .acciones-modal-eliminar {
                gap: 0.3rem;
            }

            #seccionEliminarUsuario .acciones-modal-eliminar button {
                font-size: 0.65rem;
                padding: 0.3rem 0.45rem;
            }
        }
    </style>

    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Delete Account') }}
        </h2>

        <p class="descripcion-eliminar mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
        </p>
    </header>

    <x-danger-button
        class="boton-eliminar-principal"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >
        {{ __('Delete Account') }}
    </x-danger-button>

    <x-modal
        name="confirm-user-deletion"
        :show="$errors->userDeletion->isNotEmpty()"
        focusable
    >
        <form
            method="post"
            action="{{ route('profile.destroy') }}"
            class="contenido-modal-eliminar p-6"
        >
            @csrf
            @method('delete')

            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                {{ __('Are you sure you want to delete your account?') }}
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
            </p>

            <div class="mt-6">
                <x-input-label
                    for="password"
                    value="{{ __('Password') }}"
                    class="sr-only"
                />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="password-eliminar mt-1 block w-3/4"
                    placeholder="{{ __('Password') }}"
                />

                <x-input-error
                    :messages="$errors->userDeletion->get('password')"
                    class="mt-2"
                />
            </div>

            <div class="acciones-modal-eliminar mt-6 flex justify-end">
                <x-secondary-button x-on:click="$dispatch('close')">
                    {{ __('Cancel') }}
                </x-secondary-button>

                <x-danger-button class="ms-3">
                    {{ __('Delete Account') }}
                </x-danger-button>
            </div>
        </form>
    </x-modal>

</section>