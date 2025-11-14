@extends('app')

@section('content')
<div class="min-h-screen bg-gray-50 flex flex-col items-center py-10">

    <!-- 🧠 Título del curso -->
    <div class="bg-white w-full max-w-4xl rounded-xl shadow p-6 mb-5">
        <h2 class="text-2xl font-bold text-blue-600">{{ $curso->nombre_curso }}</h2>
        <p class="text-gray-700 mt-1">
        <span class="font-semibold">Docente:</span> {{ $curso->docente }}
        </p>
    </div>

    

    <!-- 🧾 Panel de opciones -->
    <div class="bg-white w-full max-w-4xl rounded-xl shadow p-5">
        <h4 class="text-gray-700 font-semibold mb-4">Opciones del curso</h4>

        <div class="grid sm:grid-cols-2 gap-3">
            <button onclick="mostrarSeccion('participantes')" class="flex items-center gap-2 p-3 border rounded-lg hover:bg-gray-50 text-gray-700 text-sm font-medium transition">
                <i class="fa fa-users text-blue-500"></i> Ver participantes
            </button>

            <button onclick="mostrarSeccion('asistencia')" class="flex items-center gap-2 p-3 border rounded-lg hover:bg-gray-50 text-gray-700 text-sm font-medium transition">
                <i class="fa fa-calendar-check text-green-500"></i> Ver asistencia
            </button>

            <button onclick="mostrarSeccion('material')" class="flex items-center gap-2 p-3 border rounded-lg hover:bg-gray-50 text-gray-700 text-sm font-medium transition">
                <i class="fa fa-book-open text-yellow-500"></i> Material de apoyo
            </button>


            <button onclick="mostrarSeccion('enlace')" class="flex items-center gap-2 p-3 border rounded-lg hover:bg-gray-50 text-gray-700 text-sm font-medium transition">
                <i class="fa fa-video text-purple-500"></i> Enlace de clases
            </button>
        </div>
    </div>

    <!-- 🔽 Panel dinámico -->
    <div id="panel-dinamico" class="w-full max-w-4xl mt-6"></div>
</div>

<!-- ⚙️ Script funcional -->
<script>
function mostrarSeccion(seccion) {
    const cont = document.getElementById('panel-dinamico');
    let html = '';

    // 👥 Participantes
    if (seccion === 'participantes') {
        html = `
        <div class="bg-white shadow rounded-xl p-5">
            <h5 class="font-semibold text-gray-700 mb-3">👥 Participantes</h5>
            <ul class="divide-y divide-gray-200 text-sm text-gray-700">
                @foreach($alumnos as $a)
                    <li class="py-2">{{ $a->nombres }} {{ $a->apellidos }}</li>
                @endforeach
            </ul>
        </div>`;
    }

    // 📅 Asistencia
    if (seccion === 'asistencia') {
        html = `
        <div class="bg-white shadow rounded-xl p-5">
            <h5 class="font-semibold text-gray-700 mb-3">📅 Registro de Asistencia</h5>

            @if($asistencias->count() > 0)
                <table class="w-full border border-gray-200 text-sm text-gray-700 rounded-lg overflow-hidden">
                    <thead class="bg-gray-100 text-gray-800">
                        <tr>
                            <th class="py-2 px-3 text-left">Fecha</th>
                            <th class="py-2 px-3 text-left">Estado</th>
                            <th class="py-2 px-3 text-left">Puntaje</th>
                            <th class="py-2 px-3 text-left">Observación</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($asistencias as $asis)
                        <tr class="border-t">
                            <td class="py-2 px-3">{{ $asis->fecha }}</td>
                            <td class="py-2 px-3">
                                @if($asis->estado == 'Presente')
                                    <span class="text-green-600 font-semibold">Presente</span>
                                @elseif($asis->estado == 'Ausente')
                                    <span class="text-red-500 font-semibold">Ausente</span>
                                @elseif($asis->estado == 'Justificado')
                                    <span class="text-yellow-600 font-semibold">Justificado</span>
                                @else
                                    <span class="text-gray-500">-</span>
                                @endif
                            </td>
                            <td class="py-2 px-3">{{ $asis->puntaje ?? '—' }}</td>
                            <td class="py-2 px-3">{{ $asis->observacion ?? '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="text-gray-600 text-sm">No hay registros de asistencia disponibles.</p>
            @endif
        </div>`;
    }

    // 📘 Material
    if (seccion === 'material') {
        html = `
        <div class="bg-white shadow rounded-xl p-5">
            <h5 class="font-semibold text-gray-700 mb-4 flex items-center gap-2">
                <i class="fa fa-layer-group text-blue-500"></i> Temas por semana
            </h5>

            <div class="space-y-6">
                @foreach($temas as $tema)
                    <div class="border border-gray-200 rounded-xl p-5 bg-white shadow-sm">
                        <h5 class="text-blue-600 font-bold text-base mb-1">Semana {{ $tema->semana }}</h5>
                        <p class="font-semibold text-gray-800 mb-1">{{ $tema->titulo_tema }}</p>
                        <p class="text-gray-600 text-sm mb-3">{{ $tema->contenido }}</p>

                        @php
                            $materialesSemana = $materiales->where('semana', $tema->semana);
                        @endphp

                        @if($materialesSemana->count() > 0)
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <h6 class="text-green-700 font-semibold mb-2 flex items-center gap-2">
                                    <i class="fa fa-book-open text-green-600"></i>
                                    Materiales de apoyo
                                </h6>

                                <ul class="space-y-1 text-sm">
                                    @foreach($materialesSemana as $mat)
                                        <li class="flex items-center gap-2">
                                            <i class="fa fa-file text-gray-500"></i>
                                            <a href="{{ asset('storage/' . $mat->archivo_pdf) }}" target="_blank" class="text-blue-600 hover:underline">
                                               {{ $mat->titulo }}
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @else
                            <p class="text-gray-400 italic text-sm">
                                No hay materiales disponibles para esta semana.
                            </p>
                        @endif
                    </div>
                @endforeach
            </div>
        </div>`;
    }

    

    //  Enlace de clases
    if (seccion === 'enlace') {
        html = `
        <div class="bg-white shadow rounded-xl p-5">
            <h5 class="font-semibold text-gray-700 mb-3">🎥 Enlace de clases</h5>
            @if(!empty($enlaceClase))
                <p class="text-gray-700 text-sm">
                    <strong>Enlace activo:</strong>
                    <a href="{{ $enlaceClase }}" target="_blank" class="text-blue-600 hover:underline">
                        Ir a clase virtual
                    </a>
                </p>
            @else
                <p class="text-gray-400 text-sm italic">No hay enlace de clase disponible por el momento.</p>
            @endif
        </div>`;
    }

    cont.innerHTML = html;
    cont.scrollIntoView({ behavior: 'smooth' });
}
</script>
@endsection