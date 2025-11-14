@extends('app')

@section('content')
<div class="container mx-auto p-4">
    <h2 class="text-2xl font-bold mb-4">🗓️ Horario General de Docentes</h2>

    @if($periodo)
        <p class="mb-4 text-gray-700">
            <strong>Período activo:</strong> {{ $periodo->nombre }}
        </p>
    @endif

    <!-- 🔽 Dropdown de docentes -->
    <form method="GET" action="{{ route('tutor.horario') }}" class="mb-4 flex items-center gap-3">
        <label for="docente" class="text-gray-700 font-semibold">Filtrar por docente:</label>
        <select name="docente" id="docente" class="border rounded-lg p-2">
            <option value="">-- Todos --</option>
            @foreach($docentes as $d)
                <option value="{{ $d->id_docente }}" {{ $idDocente == $d->id_docente ? 'selected' : '' }}>
                    {{ $d->nombre }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
            Filtrar
        </button>
    </form>

    <!-- 🧾 Tabla de horarios -->
    <table class="table-auto w-full border-collapse border border-gray-300">
        <thead class="bg-gray-100">
            <tr>
                <th class="border p-2">Día</th>
                <th class="border p-2">Hora Inicio</th>
                <th class="border p-2">Hora Fin</th>
                <th class="border p-2">Aula</th>
                <th class="border p-2">Curso</th>
                <th class="border p-2">Docente</th>
            </tr>
        </thead>
        <tbody>
            @forelse($horarios as $h)
                <tr class="hover:bg-gray-50">
                    <td class="border p-2 capitalize">{{ $h->dia }}</td>
                    <td class="border p-2">{{ $h->hora_inicio }}</td>
                    <td class="border p-2">{{ $h->hora_fin }}</td>
                    <td class="border p-2">{{ $h->aula }}</td>
                    <td class="border p-2">{{ $h->curso }}</td>
                    <td class="border p-2">{{ $h->docente ?? '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="border p-2 text-center text-gray-500">No hay horarios registrados.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
