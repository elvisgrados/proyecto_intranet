@extends('app')

@section('content')
<div class="max-w-6xl mx-auto mt-8 px-6">
    <!-- 🧠 Título -->
    <div class="bg-white shadow-md rounded-2xl p-6 mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-blue-700 flex items-center gap-2">
            📅 Horario de Clases
        </h2>
        <span class="text-gray-400 text-sm">Actualizado {{ now()->format('d/m/Y') }}</span>
    </div>

    @if($horarios->isEmpty())
        <div class="bg-white shadow-md rounded-xl p-10 text-center text-gray-500 text-lg">
            Aún no tienes horarios registrados.
        </div>
    @else
        <!-- 📋 Tabla -->
        <div class="bg-white shadow-md rounded-2xl overflow-hidden">
            <table class="min-w-full text-sm text-gray-700">
                <thead class="bg-blue-600 text-white text-left text-xs uppercase tracking-wider">
                    <tr>
                        <th class="py-3 px-4">Día</th>
                        <th class="py-3 px-4 text-center">Hora Inicio</th>
                        <th class="py-3 px-4 text-center">Hora Fin</th>
                        <th class="py-3 px-4">Curso</th>
                        <th class="py-3 px-4 text-center">Aula</th>
                        <th class="py-3 px-4">Docente</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($horarios as $h)
                    <tr class="hover:bg-blue-50 transition duration-150">
                        <td class="py-3 px-4 font-semibold text-gray-800">
                            {{ ucfirst($h->dia) }}
                        </td>
                        <td class="py-3 px-4 text-center text-gray-600">
                            {{ \Carbon\Carbon::parse($h->hora_inicio)->format('H:i') }}
                        </td>
                        <td class="py-3 px-4 text-center text-gray-600">
                            {{ \Carbon\Carbon::parse($h->hora_fin)->format('H:i') }}
                        </td>
                        <td class="py-3 px-4 font-semibold text-gray-900">
                            {{ $h->nombre_curso }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            <span class="inline-block bg-blue-100 text-blue-700 px-3 py-1 rounded-full text-xs font-medium">
                                {{ $h->aula }}
                            </span>
                        </td>
                        <td class="py-3 px-4 text-gray-800">
                            {{ $h->docente }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection