@extends('app')

@section('content')

<div class="p-6 bg-white rounded-2xl shadow">
    <h4 class="text-2xl font-bold text-gray-800 flex items-center gap-2">
        📘 Mis Cursos
    </h4>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 mt-6">
        @foreach($cursos as $curso)
        <div class="bg-white border border-gray-100 rounded-xl shadow-sm p-5 hover:shadow-md transition">
            <h6 class="font-semibold text-gray-800 text-lg">{{ $curso->nombre_curso }}</h6>
            <p class="text-gray-600 mt-1">
                <span class="font-semibold">Docente:</span> {{ $curso->docente }}
            </p>
            <a href="{{ route('alumno.curso_detalle', $curso->id_curso) }}"
               class="inline-block mt-4 px-4 py-2 border border-blue-500 text-blue-600 rounded-md text-sm hover:bg-blue-500 hover:text-white transition">
                Ver detalles
            </a>
        </div>
        @endforeach
    </div>
</div>

@endsection