<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h2 class="text-2xl font-bold mb-6">Crear Nueva Tanda</h2>

                @if ($errors->any())
                    <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('tandas.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <!-- Nombre -->
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Nombre de la Tanda</label>
                            <input type="text" name="nombre" class="w-full border-gray-300 rounded-md shadow-sm" required value="{{ old('nombre') }}">
                        </div>

                        <!-- Monto de Cuota -->
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Monto Base por Cuota</label>
                            <input type="number" step="0.01" name="monto_cuota" class="w-full border-gray-300 rounded-md shadow-sm" required value="{{ old('monto_cuota') }}">
                        </div>

                        <!-- Frecuencia -->
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Frecuencia de Aportación</label>
                            <select name="frecuencia" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="semanal" {{ old('frecuencia') == 'semanal' ? 'selected' : '' }}>Semanal</option>
                                <option value="quincenal" {{ old('frecuencia') == 'quincenal' ? 'selected' : '' }}>Quincenal</option>
                                <option value="mensual" {{ old('frecuencia') == 'mensual' ? 'selected' : '' }}>Mensual</option>
                            </select>
                        </div>

                        <!-- Cuotas por Entrega -->
                        <div>
                            <label class="block font-medium text-sm text-gray-700">¿Cada cuántas cuotas se entrega?</label>
                            <input type="number" name="cuotas_por_entrega" min="1" value="{{ old('cuotas_por_entrega', 1) }}" class="w-full border-gray-300 rounded-md shadow-sm" required>
                        </div>

                        <!-- Total Participantes -->
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Total de Participantes (N)</label>
                            <input type="number" name="total_participantes" min="2" class="w-full border-gray-300 rounded-md shadow-sm" required value="{{ old('total_participantes') }}">
                        </div>

                        <!-- Modalidad -->
                        <div>
                            <label class="block font-medium text-sm text-gray-700">Modalidad</label>
                            <select name="modalidad" class="w-full border-gray-300 rounded-md shadow-sm" required>
                                <option value="sin_cero_sin_cargo" {{ old('modalidad') == 'sin_cero_sin_cargo' ? 'selected' : '' }}>Sin Cero, Sin Cargo (1 a N)</option>
                                <option value="con_cero" {{ old('modalidad') == 'con_cero' ? 'selected' : '' }}>Con Turno Cero (Organizador)</option>
                                <option value="sin_cero_cargo_gradual" {{ old('modalidad') == 'sin_cero_cargo_gradual' ? 'selected' : '' }}>Sin Cero, Cargo Gradual</option>
                            </select>
                        </div>

                        <!-- Fecha de Inicio -->
                        <div class="md:col-span-2">
                            <label class="block font-medium text-sm text-gray-700">Fecha de Inicio / Primera Cuota</label>
                            <input type="date" name="fecha_inicio" class="w-full border-gray-300 rounded-md shadow-sm" required value="{{ old('fecha_inicio', date('Y-m-d')) }}">
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700 font-medium">
                            Guardar y Generar Calendario
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>