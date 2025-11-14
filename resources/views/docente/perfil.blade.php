@extends('app')

@section('content')
<div class="min-h-screen bg-gray-100 py-8 px-4">

    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl p-8 border border-purple-100">

        <!-- Encabezado -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-purple-700">Perfil del Docente</h2>
            
            <a href="{{ route('docente.configuracion') }}" 
                class="bg-purple-600 hover:bg-purple-700 text-white px-5 py-2 rounded-xl shadow-md transition">
                ✏️ Editar Perfil
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">

            <!-- FOTO DEL DOCENTE -->
            <div class="flex justify-center">
                @if($usuario->foto)
                    <img src="{{ asset($usuario->foto) }}" 
                        class="w-40 h-40 object-cover rounded-full border-4 border-purple-400 shadow-md">
                @else
                    <div class="w-40 h-40 rounded-full bg-purple-100 flex items-center justify-center text-purple-600 text-4xl font-bold shadow-md">
                        {{ strtoupper(substr($usuario->nombres, 0, 1)) }}
                    </div>
                @endif
            </div>

            <!-- INFORMACIÓN -->
            <div class="md:col-span-2 space-y-3 text-gray-700">
                <p class="text-lg"><strong class="text-purple-700">Nombre:</strong> {{ $usuario->nombres ?? 'Sin datos' }}</p>
                <p class="text-lg"><strong class="text-purple-700">Apellidos:</strong> {{ $usuario->apellidos ?? 'Sin datos' }}</p>
                <p><strong class="text-purple-700">Email:</strong> {{ $usuario->email ?? 'Sin datos' }}</p>
                <p><strong class="text-purple-700" >Contraseña:</strong> {{ $usuario->password }} </p>
                <p><strong class="text-purple-700">Teléfono:</strong> {{ $usuario->telefono ?? 'Sin datos' }}</p>
            </div>

        </div>

        <!-- CURSOS ASIGNADOS -->
        <div class="mt-8">
            <h3 class="text-xl font-semibold text-purple-700 mb-3">Cursos Asignados</h3>

            @if($cursos->isEmpty())
                <p class="text-gray-500">No tiene cursos asignados.</p>
            @else
                <div class="flex flex-wrap gap-2">
                    @foreach($cursos as $curso)
                        <span class="bg-purple-100 text-purple-700 px-4 py-1 rounded-full text-sm font-medium border border-purple-200 shadow-sm">
                             {{ $curso->nombre_curso }}
                        </span>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
