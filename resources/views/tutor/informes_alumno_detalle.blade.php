@extends('app')

@section('content')
<div class="max-w-5xl mx-auto p-6">
  <div class="flex items-center justify-between mb-6">
    <h2 class="text-3xl font-extrabold text-gray-800">Detalle del Alumno: <span class="text-3xl font-bold">{{ $alumno->nombres ?? '' }} {{ $alumno->apellidos ?? '' }}</span></h2>
    <a href="{{ route('tutor.informes') }}" class="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-medium transition">
      ⬅ Volver
    </a>
  </div>

  <div class="bg-white shadow-xl rounded-2xl p-6 border border-gray-100">
    <!-- DATOS DEL ALUMNO -->
    <p class="text-lg mb-4">
      <strong class="text-gray-700">Alumno:</strong>
      <span class="text-gray-900 font-medium">
        {{ $alumno->nombres ?? '' }} {{ $alumno->apellidos ?? '' }}
      </span>
    </p>

    @if(isset($alumno->dni))
    <p class="text-sm text-gray-600 mb-4">
      <strong class="text-gray-700">DNI:</strong> {{ $alumno->dni ?? '—' }} |
      <strong class="text-gray-700">Correo:</strong> {{ $alumno->email ?? '—' }}
    </p>
    @endif

    @if(isset($promedio))
    <div class="mb-6">
      <p class="text-gray-700 font-semibold">
        Promedio general: 
        <span class="text-indigo-600 text-lg">{{ $promedio ?? '—' }}</span>
      </p>
    </div>
    @endif

    <!-- SECCIÓN NOTAS -->
    <h3 class="text-xl font-semibold text-indigo-700 mt-6 mb-2 flex items-center gap-2">
       Notas
    </h3>
    <div class="overflow-x-auto">
      <table class="w-full border-collapse rounded-lg overflow-hidden shadow-sm">
        <thead class="bg-indigo-600 text-white">
          <tr>
            <th class="py-2 px-3 text-left">Evaluación</th>
            <th class="py-2 px-3 text-left">Puntaje</th>
            <th class="py-2 px-3 text-left">Fecha</th>
          </tr>
        </thead>
        <tbody>
          @forelse($notas as $n)
            <tr class="border-b hover:bg-indigo-50 transition">
              <td class="py-2 px-3">{{ $n->evaluacion ?? '—' }}</td>
              <td class="py-2 px-3 font-semibold">{{ $n->puntaje ?? '—' }}</td>
              <td class="py-2 px-3">{{ $n->fecha_registro ?? '—' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="text-center text-gray-500 py-3">Sin notas registradas</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- SECCIÓN ASISTENCIAS -->
    <h3 class="text-xl font-semibold text-indigo-700 mt-8 mb-2 flex items-center gap-2">
      Asistencias
    </h3>
    <div class="overflow-x-auto">
      <table class="w-full border-collapse rounded-lg overflow-hidden shadow-sm">
        <thead class="bg-indigo-600 text-white">
          <tr>
            <th class="py-2 px-3 text-left">Fecha</th>
            <th class="py-2 px-3 text-left">Estado</th>
            <th class="py-2 px-3 text-left">Puntaje</th>
          </tr>
        </thead>
        <tbody>
          @forelse($asistencias as $as)
            <tr class="border-b hover:bg-indigo-50 transition">
              <td class="py-2 px-3">{{ $as->fecha ?? '—' }}</td>
              <td class="py-2 px-3">{{ ucfirst($as->estado ?? '-') }}</td>
              <td class="py-2 px-3 font-semibold">{{ $as->puntaje ?? '—' }}</td>
            </tr>
          @empty
            <tr>
              <td colspan="3" class="text-center text-gray-500 py-3">Sin registros de asistencia</td>
            </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- BOTÓN PDF -->
    <div class="text-right mt-8">
      <a href="{{ route('tutor.descargarPDF', $alumno->id_alumno) }}"
        class="inline-flex items-center gap-2 bg-red-600 hover:bg-red-700 text-white px-5 py-2.5 rounded-lg shadow-md font-medium transition">
         Descargar PDF
      </a>
    </div>
  </div>
</div>
@endsection
