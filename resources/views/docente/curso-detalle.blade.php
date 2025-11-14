@extends('app')

@section('content')
<div class="w-full px-6 py-6" x-data="{ openModal: false }">

    <!-- TÍTULO PRINCIPAL -->
    <h1 class="text-3xl font-bold text-gray-800 mb-6">
        {{ $curso->nombre_curso }}
    </h1>

    <!-- INFORMACIÓN DEL CURSO -->
    <section class="bg-white shadow-md rounded-xl p-6 mb-8">
        <h2 class="text-xl font-semibold text-gray-800 mb-3">📘 Información del curso</h2>
        <p class="text-gray-700"><strong>Descripción:</strong> {{ $curso->descripcion ?? 'Sin descripción.' }}</p>
        <p class="text-gray-700 mt-2"><strong>Alumnos activos:</strong> {{ $cantidadAlumnos }}</p>
    </section>

    <!-- BOTÓN PARA ABRIR MODAL -->
    <div class="mb-8">
        <button @click="openModal = true"
                class="px-5 py-2 bg-blue-600 text-white rounded-lg font-semibold hover:bg-blue-700 transition">
            📤 Subir material de apoyo
        </button>
    </div>

    <!-- MODAL -->
    <div x-show="openModal"
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         x-transition
         x-cloak>
        <div class="bg-white rounded-xl shadow-lg w-full max-w-lg p-6 relative">

            <!-- Botón Cerrar -->
            <button @click="openModal = false"
                    class="absolute top-3 right-3 text-gray-500 hover:text-gray-700 text-xl font-bold">
                ×
            </button>

            <h2 class="text-xl font-semibold text-gray-800 mb-4">📎 Subir material de apoyo</h2>

            <form action="{{ route('docente.materiales.subir', $curso->id_curso) }}"
                  method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-gray-700 font-semibold">Semana:</label>
                    <input type="number" name="semana" min="1" required class="border rounded-md p-2 w-32">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold">Título:</label>
                    <input type="text" name="titulo" required class="border rounded-md p-2 w-full">
                </div>

                <div>
                    <label class="block text-gray-700 font-semibold">Archivo PDF:</label>
                    <input type="file" name="archivo_pdf" accept="application/pdf" required class="border rounded-md p-2 w-full">
                </div>

                <div class="flex justify-end gap-3">
                    <button type="button" @click="openModal = false"
                            class="px-4 py-2 bg-gray-400 text-white rounded-lg hover:bg-gray-500">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700">
                        Subir
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- TEMAS POR SEMANA -->
    <section>
        <h2 class="text-xl font-semibold text-gray-800 mb-4">📅 Temas por semana</h2>

        @forelse($temasPorSemana as $semana => $temas)
            <div class="bg-white shadow-sm border border-gray-200 rounded-lg p-5 mb-6">

                <h3 class="text-lg font-bold text-blue-700 mb-3">
                    Semana {{ $semana }}
                </h3>

                @foreach($temas as $tema)
                    <div class="mb-4 pb-4 border-b border-gray-200 last:border-none">
                        <h4 class="text-lg font-semibold text-gray-800">{{ $tema->titulo_tema }}</h4>
                        <p class="text-gray-600 mt-1">{{ $tema->contenido }}</p>
                    </div>
                @endforeach

                <!-- MATERIALES DE APOYO -->
                @php $semanaInt = (int)$semana; @endphp
                @if(isset($materialesPorSemana[$semanaInt]))
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4 mt-4">
                        <h4 class="text-md font-semibold text-green-700 mb-2">📚 Materiales de apoyo</h4>
                        @foreach($materialesPorSemana[$semanaInt] as $mat)
                            <a href="{{ asset('storage/'.$mat->archivo_pdf) }}" target="_blank"
                               class="block mb-1 text-blue-700 hover:underline">
                               📄 {{ $mat->titulo }}
                            </a>
                        @endforeach
                    </div>
                @endif

            </div>
        @empty
            <p class="text-gray-500 italic">No hay temas registrados aún.</p>
        @endforelse
    </section>

    <!-- BOTÓN VOLVER -->
    <div class="mt-6">
        <a href="{{ url('docente/cursos') }}"
           class="px-5 py-2 bg-gray-700 text-white rounded-lg font-semibold hover:bg-gray-800 transition">
            ⬅ Volver
        </a>
    </div>

</div>
@endsection
