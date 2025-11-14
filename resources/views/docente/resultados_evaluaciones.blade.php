@extends('app')

@section('content')
<div class="w-full px-6 py-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">📊 Resultados — Examen General (Resumen)</h1>

    <div class="bg-white shadow-md rounded-xl p-6 overflow-x-auto">
        <table class="min-w-full border border-gray-200 rounded-lg">
            <thead class="bg-gray-100">
                <tr class="text-gray-700 text-left">
                    <th class="py-2 px-4 border-b">#</th>
                    <th class="py-2 px-4 border-b">Alumno</th>
                    <th class="py-2 px-4 border-b">DNI</th>
                    <th class="py-2 px-4 border-b">Puntaje</th>
                    <th class="py-2 px-4 border-b">Fecha</th>
                </tr>
            </thead>
            <tbody>
                @forelse($resultados as $index => $res)
                    <tr class="hover:bg-gray-50">
                        <td class="py-2 px-4 border-b align-top">{{ $index + 1 }}</td>
                        <td class="py-2 px-4 border-b align-top">{{ $res->nombres }} {{ $res->apellidos }}</td>
                        <td class="py-2 px-4 border-b align-top">{{ $res->dni }}</td>
                        <td class="py-2 px-4 border-b font-semibold align-top">
                            {{ $res->puntaje !== null ? $res->puntaje : '—' }}
                        </td>
                        <td class="py-2 px-4 border-b align-top">
                            {{ $res->fecha_registro ? \Carbon\Carbon::parse($res->fecha_registro)->format('d/m/Y H:i') : '—' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="py-4 text-center text-gray-500 italic">
                            No hay resultados registrados aún.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
