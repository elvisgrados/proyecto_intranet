@extends('app')

@section('content')
<div class="w-full px-6 py-6">

    <!-- TÍTULO -->
    <h1 class="text-3xl font-bold text-gray-800 mb-6 flex items-center gap-2">
         Mis Cursos
    </h1>

    <!-- LISTA DE CURSOS -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">

        @forelse($asignaciones as $asignacion)
            <a href="{{ route('docente.curso.ver', $asignacion->curso->id_curso) }}"
               class="block bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl hover:-translate-y-1 transition-all duration-200">

                <!-- IMAGEN DEL CURSO -->
                <div class="h-40 w-full overflow-hidden">
                    <img src="{{ asset('raz_verbal.jpeg') }}" 
                         alt="Imagen del curso"
                         class="w-full h-full object-cover">
                </div>

                <!-- INFORMACIÓN -->
                <div class="p-4 text-center">
                    <h3 class="text-lg font-bold text-gray-800 mb-1">
                        {{ $asignacion->curso->nombre_curso }}
                    </h3>
                    <p class="text-sm text-gray-500">
                        {{ $asignacion->curso->codigo ?? 'Código no especificado' }}
                    </p>
                </div>
            </a>
        @empty
            <div class="col-span-full text-center text-gray-500 py-10">
                No tienes cursos asignados actualmente.
            </div>
        @endforelse

    </div>

</div>
@endsection
