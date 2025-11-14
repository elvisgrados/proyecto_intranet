@extends('app')

@section('content')
<div class="p-8">
    <!-- 🧾 Encabezado -->
    <div class="bg-white rounded-2xl shadow p-6 mb-6 flex items-center gap-3">
        <i class="fa-solid fa-chart-column text-3xl text-blue-600"></i>
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Resultados </h2>
            <p class="text-gray-500 text-sm">Visualiza tus puntajes obtenidos en los simulacros o evaluaciones.</p>
        </div>
    </div>

  

    <!-- 📋 Tabla de resultados -->
    <div class="bg-white rounded-2xl shadow p-6">
        <h4 class="text-lg font-semibold text-gray-700 mb-4">Detalle de resultados</h4>

        @if($resultados->isEmpty())
            <p class="text-gray-500 text-center py-4">Aún no tienes resultados registrados.</p>
        @else
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-gray-100">
                    <tr class="text-left text-gray-600 uppercase text-xs tracking-wider">
                        <th class="py-3 px-4">#</th>
                        <th class="py-3 px-4">Examen</th>
                        <th class="py-3 px-4">Puntaje</th>
                        <th class="py-3 px-4">Fecha</th>
                        <th class="py-3 px-4">Rendimiento</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($resultados as $i => $res)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-4">{{ $i + 1 }}</td>
                        <td class="py-3 px-4 font-medium text-gray-800">{{ $res->titulo }}</td>
                        <td class="py-3 px-4 font-semibold text-blue-600">{{ number_format($res->puntaje_total, 2) }}</td>
                        <td class="py-3 px-4 text-gray-600">{{ \Carbon\Carbon::parse($res->fecha_registro)->format('d/m/Y H:i') }}</td>
                        <td class="py-3 px-4">
                            @php
                                $color = $res->puntaje_total >= 14 ? 'bg-green-100 text-green-700' :
                                         ($res->puntaje_total >= 10 ? 'bg-yellow-100 text-yellow-700' : 'bg-red-100 text-red-700');
                                $texto = $res->puntaje_total >= 14 ? 'Excelente' :
                                         ($res->puntaje_total >= 10 ? 'Regular' : 'Bajo');
                            @endphp
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $color }}">
                                {{ $texto }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</div>


@endsection