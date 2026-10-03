<x-app-layout>

    <div class="py-3 sm:py-6 lg:py-12">

        <div class="max-w-7xl mx-auto px-2 sm:px-4 lg:px-8">

            <div class="bg-white
                        overflow-hidden
                        shadow-sm
                        rounded-lg
                        p-2.5
                        sm:p-5
                        lg:p-6">

                <!-- ================================================= -->
                <!-- TÍTULO -->
                <!-- ================================================= -->

                <h2 class="text-base
                           sm:text-xl
                           lg:text-2xl
                           font-bold
                           mb-3
                           sm:mb-6
                           text-gray-800">

                    Crear Nueva Tanda

                </h2>


                <!-- ================================================= -->
                <!-- ERRORES -->
                <!-- ================================================= -->

                @if ($errors->any())

                    <div class="mb-3
                                sm:mb-4
                                bg-red-100
                                border
                                border-red-400
                                text-red-700
                                px-2.5
                                sm:px-4
                                py-2
                                sm:py-3
                                rounded">

                        <ul class="text-[11px] sm:text-sm space-y-0.5 sm:space-y-1">

                            @foreach ($errors->all() as $error)

                                <li>
                                    {{ $error }}
                                </li>

                            @endforeach

                        </ul>

                    </div>

                @endif


                <!-- ================================================= -->
                <!-- FORMULARIO -->
                <!-- ================================================= -->

                <form action="{{ route('tandas.store') }}" method="POST">

                    @csrf


                    <!-- ================================================= -->
                    <!-- CAMPOS -->
                    <!-- ================================================= -->

                    <div class="grid
                                grid-cols-1
                                md:grid-cols-2
                                gap-2.5
                                sm:gap-4
                                mb-4
                                sm:mb-6">


                        <!-- ================================================= -->
                        <!-- NOMBRE -->
                        <!-- ================================================= -->

                        <div>

                            <label class="block
                                          font-medium
                                          text-[11px]
                                          sm:text-sm
                                          text-gray-700
                                          mb-0.5
                                          sm:mb-1">

                                Nombre de la Tanda

                            </label>


                            <input type="text"
                                   name="nombre"
                                   class="w-full
                                          h-8
                                          sm:h-10
                                          border-gray-300
                                          rounded-md
                                          shadow-sm
                                          px-2
                                          sm:px-3
                                          py-1
                                          text-xs
                                          sm:text-base
                                          focus:border-indigo-500
                                          focus:ring-indigo-500"
                                   required
                                   value="{{ old('nombre') }}">

                        </div>



                        <!-- ================================================= -->
                        <!-- MONTO DE CUOTA -->
                        <!-- ================================================= -->

                        <div>

                            <label class="block
                                          font-medium
                                          text-[11px]
                                          sm:text-sm
                                          text-gray-700
                                          mb-0.5
                                          sm:mb-1">

                                Monto Base por Cuota

                            </label>


                            <input type="number"
                                   step="0.01"
                                   name="monto_cuota"
                                   class="w-full
                                          h-8
                                          sm:h-10
                                          border-gray-300
                                          rounded-md
                                          shadow-sm
                                          px-2
                                          sm:px-3
                                          py-1
                                          text-xs
                                          sm:text-base
                                          focus:border-indigo-500
                                          focus:ring-indigo-500"
                                   required
                                   value="{{ old('monto_cuota') }}">

                        </div>



                        <!-- ================================================= -->
                        <!-- FRECUENCIA -->
                        <!-- ================================================= -->

                        <div>

                            <label class="block
                                          font-medium
                                          text-[11px]
                                          sm:text-sm
                                          text-gray-700
                                          mb-0.5
                                          sm:mb-1">

                                Frecuencia de Aportación

                            </label>


                            <select name="frecuencia"
                                    class="w-full
                                           h-8
                                           sm:h-10
                                           border-gray-300
                                           rounded-md
                                           shadow-sm
                                           px-2
                                           sm:px-3
                                           py-1
                                           text-xs
                                           sm:text-base
                                           focus:border-indigo-500
                                           focus:ring-indigo-500"
                                    required>

                                <option value="semanal"
                                    {{ old('frecuencia') == 'semanal' ? 'selected' : '' }}>

                                    Semanal

                                </option>

                                <option value="quincenal"
                                    {{ old('frecuencia') == 'quincenal' ? 'selected' : '' }}>

                                    Quincenal

                                </option>

                                <option value="mensual"
                                    {{ old('frecuencia') == 'mensual' ? 'selected' : '' }}>

                                    Mensual

                                </option>

                            </select>

                        </div>



                        <!-- ================================================= -->
                        <!-- CUOTAS POR ENTREGA -->
                        <!-- ================================================= -->

                        <div>

                            <label class="block
                                          font-medium
                                          text-[11px]
                                          sm:text-sm
                                          text-gray-700
                                          mb-0.5
                                          sm:mb-1">

                                ¿Cada cuántas cuotas se entrega?

                            </label>


                            <input type="number"
                                   name="cuotas_por_entrega"
                                   min="1"
                                   value="{{ old('cuotas_por_entrega', 1) }}"
                                   class="w-full
                                          h-8
                                          sm:h-10
                                          border-gray-300
                                          rounded-md
                                          shadow-sm
                                          px-2
                                          sm:px-3
                                          py-1
                                          text-xs
                                          sm:text-base
                                          focus:border-indigo-500
                                          focus:ring-indigo-500"
                                   required>

                        </div>



                        <!-- ================================================= -->
                        <!-- TOTAL PARTICIPANTES -->
                        <!-- ================================================= -->

                        <div>

                            <label class="block
                                          font-medium
                                          text-[11px]
                                          sm:text-sm
                                          text-gray-700
                                          mb-0.5
                                          sm:mb-1">

                                Total de Participantes (N)

                            </label>


                            <input type="number"
                                   name="total_participantes"
                                   min="2"
                                   class="w-full
                                          h-8
                                          sm:h-10
                                          border-gray-300
                                          rounded-md
                                          shadow-sm
                                          px-2
                                          sm:px-3
                                          py-1
                                          text-xs
                                          sm:text-base
                                          focus:border-indigo-500
                                          focus:ring-indigo-500"
                                   required
                                   value="{{ old('total_participantes') }}">

                        </div>



                        <!-- ================================================= -->
                        <!-- MODALIDAD -->
                        <!-- ================================================= -->

                        <div>

                            <label class="block
                                          font-medium
                                          text-[11px]
                                          sm:text-sm
                                          text-gray-700
                                          mb-0.5
                                          sm:mb-1">

                                Modalidad

                            </label>


                            <select name="modalidad"
                                    class="w-full
                                           h-8
                                           sm:h-10
                                           border-gray-300
                                           rounded-md
                                           shadow-sm
                                           px-2
                                           sm:px-3
                                           py-1
                                           text-xs
                                           sm:text-base
                                           focus:border-indigo-500
                                           focus:ring-indigo-500"
                                    required>

                                <option value="sin_cero_sin_cargo"
                                    {{ old('modalidad') == 'sin_cero_sin_cargo' ? 'selected' : '' }}>

                                    Sin Cero, Sin Cargo (1 a N)

                                </option>

                                <option value="con_cero"
                                    {{ old('modalidad') == 'con_cero' ? 'selected' : '' }}>

                                    Con Turno Cero (Organizador)

                                </option>

                                <option value="sin_cero_cargo_gradual"
                                    {{ old('modalidad') == 'sin_cero_cargo_gradual' ? 'selected' : '' }}>

                                    Sin Cero, Cargo Gradual

                                </option>

                            </select>

                        </div>



                        <!-- ================================================= -->
                        <!-- FECHA DE INICIO -->
                        <!-- ================================================= -->

                        <div class="md:col-span-2">

                            <label class="block
                                          font-medium
                                          text-[11px]
                                          sm:text-sm
                                          text-gray-700
                                          mb-0.5
                                          sm:mb-1">

                                Fecha de Inicio / Primera Cuota

                            </label>


                            <input type="date"
                                   name="fecha_inicio"
                                   class="w-full
                                          h-8
                                          sm:h-10
                                          border-gray-300
                                          rounded-md
                                          shadow-sm
                                          px-2
                                          sm:px-3
                                          py-1
                                          text-xs
                                          sm:text-base
                                          focus:border-indigo-500
                                          focus:ring-indigo-500"
                                   required
                                   value="{{ old('fecha_inicio', date('Y-m-d')) }}">

                        </div>

                    </div>



                    <!-- ================================================= -->
                    <!-- BOTÓN -->
                    <!-- ================================================= -->

                    <div class="flex
                                flex-col
                                sm:flex-row
                                sm:justify-end
                                gap-2">

                        <button type="submit"
                                class="w-full
                                       sm:w-auto
                                       bg-indigo-600
                                       text-white
                                       px-4
                                       sm:px-6
                                       py-2
                                       sm:py-2
                                       rounded-md
                                       hover:bg-indigo-700
                                       font-medium
                                       text-xs
                                       sm:text-base
                                       transition
                                       shadow-sm">

                            Guardar y Generar Calendario

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>