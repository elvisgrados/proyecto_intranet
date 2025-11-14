@extends('app')

@section('content')
<body class="bg-gray-100 font-sans text-gray-800">

<div class="min-h-screen p-6 space-y-8">

  <!-- TÍTULO -->
  <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
    <h1 class="text-3xl font-bold text-gray-900">📊 Informe General de Tutoría</h1>
    <p class="text-gray-600">Resumen académico de tus alumnos asignados.</p>
  </div>

  <!-- FILTRO DE PERÍODO -->
  <form action="{{ route('tutor.informes.filtrar') }}" method="POST"
        class="bg-white rounded-2xl shadow p-5 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
    @csrf
    <div>
      <label class="block text-sm font-medium text-gray-600 mb-1">📅 Período</label>
      <select name="periodo" class="border rounded-lg px-3 py-2 w-64 focus:ring-2 focus:ring-indigo-500" required>
        <option value="">-- Seleccionar período --</option>
        @foreach($periodos as $p)
          <option value="{{ $p->id_periodo }}"
            @if(isset($periodoSeleccionado) && $periodoSeleccionado == $p->id_periodo) selected @endif>
            {{ $p->nombre }}
          </option>
        @endforeach
      </select>
    </div>
    <button type="submit"
            class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
      🔍 Generar informe
    </button>
  </form>

  <!-- RESUMEN -->
  @if(isset($promedioGeneral))
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
      <div class="bg-white rounded-2xl shadow p-5 text-center">
        <p class="text-sm text-gray-500">Promedio general</p>
        <p class="text-4xl font-bold text-indigo-600 mt-1">{{ $promedioGeneral ?? '—' }}</p>
      </div>
      <div class="bg-white rounded-2xl shadow p-5 text-center">
        <p class="text-sm text-gray-500">Asistencia promedio</p>
        <p class="text-4xl font-bold text-green-600 mt-1">{{ $asistenciaPromedio ? $asistenciaPromedio.'%' : '—' }}</p>
      </div>
      <div class="bg-white rounded-2xl shadow p-5 text-center">
        <p class="text-sm text-gray-500">Evaluaciones realizadas</p>
        <p class="text-4xl font-bold text-blue-600 mt-1">{{ $evaluacionesRealizadas ?? 0 }}</p>
      </div>
      <div class="bg-white rounded-2xl shadow p-5 text-center">
        <p class="text-sm text-gray-500">Alumnos en riesgo</p>
        <p class="text-4xl font-bold text-red-600 mt-1">{{ $alumnosEnRiesgo ?? 0 }}</p>
      </div>
    </div>
  @endif

  <!-- TABLA -->
  <div class="bg-white rounded-2xl shadow p-6">
    <div class="flex justify-between items-center mb-5">
      <h2 class="text-xl font-semibold text-gray-800">📈 Rendimiento de alumnos asignados</h2>
      <p class="text-sm text-gray-500">Promedios globales y asistencia general</p>
    </div>

    <div class="overflow-x-auto">
      <table class="w-full border-collapse text-sm">
        <thead>
          <tr class="bg-gray-50 text-gray-600 uppercase text-xs">
            <th class="py-3 px-4 border-b">#</th>
            <th class="py-3 px-4 border-b">Alumno</th>
            <th class="py-3 px-4 border-b text-center">Promedio General</th>
            <th class="py-3 px-4 border-b text-center">Asistencia</th>
            <th class="py-3 px-4 border-b text-center">Estado</th>
            <th class="py-3 px-4 border-b text-center">Acciones</th>
          </tr>
        </thead>
        <tbody>
          @if(isset($alumnos) && count($alumnos) > 0)
            @foreach($alumnos as $idx => $a)
              <tr class="hover:bg-gray-50 transition">
                <td class="py-3 px-4 border-b">{{ $idx + 1 }}</td>
                <td class="py-3 px-4 border-b font-medium">{{ $a->nombres }} {{ $a->apellidos }}</td>
                <td class="py-3 px-4 border-b text-center font-semibold
                    {{ ($a->promedio ?? 0) >= 14 ? 'text-green-600' :
                       (($a->promedio ?? 0) >= 11 ? 'text-yellow-600' : 'text-red-600') }}">
                  {{ $a->promedio ?? '—' }}
                </td>
                <td class="py-3 px-4 border-b text-center">{{ $a->asistencia ? $a->asistencia.'%' : '—' }}</td>
                <td class="py-3 px-4 border-b text-center">
                  @switch($a->estado)
                    @case('Destacada')
                      <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Destacada</span>
                      @break
                    @case('Regular')
                      <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">Regular</span>
                      @break
                    @case('En riesgo')
                      <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-xs font-medium">En riesgo</span>
                      @break
                    @default
                      <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-xs">Sin datos</span>
                  @endswitch
                </td>
                <td class="py-3 px-4 border-b text-center">
                  <a href="{{ route('tutor.informes.alumno', $a->id_alumno) }}"
                     class="px-4 py-1.5 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition">
                    Ver detalle
                  </a>
                </td>
              </tr>
            @endforeach
          @else
            <tr>
              <td colspan="6" class="py-6 text-center text-gray-500">No hay alumnos asignados para este tutor o período.</td>
            </tr>
          @endif
        </tbody>
      </table>
    </div>
  </div>

  <!-- NOTA -->
  <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 p-5 rounded-xl">
    ⚠️ <strong>Nota:</strong> Los alumnos con estado <span class="font-semibold">"En riesgo"</span>
    requieren seguimiento académico y refuerzo personalizado.
  </div>

</div>

</body>
@endsection
