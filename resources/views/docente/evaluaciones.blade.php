@extends('app')

@section('content')

<div class="min-h-screen p-6 bg-gray-100 font-sans text-gray-800">
    <h1 class="text-2xl font-bold mb-6">Evaluaciones</h1>

    <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-xl font-semibold mb-4">Lista de Evaluaciones</h2>

        <table class="w-full border-collapse">
            <thead>
                <tr class="bg-gray-50 text-left text-gray-600 text-sm uppercase">
                    <th class="py-3 px-4 border-b">Curso</th>
                    <th class="py-3 px-4 border-b">Título</th>
                    <th class="py-3 px-4 border-b">Semana</th>
                    <th class="py-3 px-4 border-b">Fecha</th>
                </tr>
            </thead>

            <tbody class="text-sm">
                @forelse($evaluaciones as $eva)
                <tr class="hover:bg-gray-50">
                    <td class="py-3 px-4 border-b">
                        {{ $eva->curso->nombre_curso }}
                    </td>
                    <td class="py-3 px-4 border-b">{{ $eva->titulo }}</td>
                    <td class="py-3 px-4 border-b">Semana {{ $eva->semana }}</td>
                    <td class="py-3 px-4 border-b">{{ $eva->fecha }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="py-4 text-center text-gray-500">
                        No hay evaluaciones registradas todavía
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>
</div>

@endsection
