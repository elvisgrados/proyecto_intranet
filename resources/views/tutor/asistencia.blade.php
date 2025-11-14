@extends('app')

@section('content')
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Asistencia - Tutor | Academia Medallón</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

  <div class="min-h-screen p-6">
    
    @if(session('success'))
      <div class="bg-green-100 border border-green-400 text-green-800 px-4 py-3 rounded mb-5">
        {{ session('success') }}
      </div>
    @endif

    @if(session('error'))
      <div class="bg-red-100 border border-red-400 text-red-800 px-4 py-3 rounded mb-5">
        {{ session('error') }}
      </div>
    @endif

    <h1 class="text-3xl font-bold mb-6">📋 Control de Asistencia - Tutor</h1>

    <!-- FILTRO DE FECHA -->
    <form method="GET" class="mb-6 flex flex-col sm:flex-row sm:items-center gap-3 bg-white p-5 rounded-2xl shadow">
      <div>
        <label class="block text-sm font-semibold text-gray-600 mb-1">Seleccionar fecha</label>
        <input type="date" name="fecha" value="{{ $fecha }}" class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
      </div>

      <button type="submit" class="mt-3 sm:mt-6 px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
        🔍 Buscar
      </button>
    </form>

    <!-- TABLA DE ASISTENCIA -->
    <form method="POST" action="{{ route('tutor.asistencia.store') }}" class="bg-white rounded-2xl shadow p-6">
      @csrf
      <input type="hidden" name="fecha" value="{{ $fecha }}">

      <h2 class="text-xl font-semibold mb-4">Lista de alumnos asignados ({{ count($alumnos) }})</h2>

      <table class="w-full border-collapse text-sm">
        <thead>
          <tr class="bg-gray-50 text-left text-gray-600 uppercase">
            <th class="py-3 px-4 border-b">#</th>
            <th class="py-3 px-4 border-b">Alumno</th>
            <th class="py-3 px-4 border-b text-center">Estado</th>
            <th class="py-3 px-4 border-b text-center">Observación</th>
          </tr>
        </thead>
        <tbody>
          @forelse($alumnos as $index => $a)
            @php
              $asistencia = $asistencias[$a->id_alumno] ?? null;
            @endphp
            <tr class="hover:bg-gray-50">
              <td class="py-3 px-4 border-b">{{ $index + 1 }}</td>
              <td class="py-3 px-4 border-b font-medium">{{ $a->nombres }} {{ $a->apellidos }}</td>
              <td class="py-3 px-4 border-b text-center">
                <select name="alumnos[{{ $a->id_alumno }}][estado]" class="border rounded px-2 py-1 text-sm">
                  <option value="Presente" {{ $asistencia && $asistencia->estado == 'Presente' ? 'selected' : '' }}>Presente</option>
                  <option value="Ausente" {{ $asistencia && $asistencia->estado == 'Ausente' ? 'selected' : '' }}>Ausente</option>
                  <option value="Tarde" {{ $asistencia && $asistencia->estado == 'Tarde' ? 'selected' : '' }}>Tarde</option>
                  <option value="Justificado" {{ $asistencia && $asistencia->estado == 'Justificado' ? 'selected' : '' }}>Justificado</option>
                </select>
              </td>
              <td class="py-3 px-4 border-b text-center">
                <input type="text" 
                       name="alumnos[{{ $a->id_alumno }}][observacion]"
                       value="{{ $asistencia->observacion ?? '' }}"
                       class="border rounded px-2 py-1 text-sm w-48"
                       placeholder="Ej. llegó tarde">
              </td>
            </tr>
          @empty
            <tr>
              <td colspan="4" class="text-center text-gray-500 py-4">No hay alumnos asignados a este tutor.</td>
            </tr>
          @endforelse
        </tbody>
      </table>

      @if(count($alumnos) > 0)
        <div class="mt-6 text-right">
          <button type="submit" class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition">
            💾 Guardar asistencia
          </button>
        </div>
      @endif
    </form>
  </div>

</body>
</html>
@endsection
