@extends('app')

@section('content')
<div class="max-w-5xl mx-auto p-6 bg-white rounded-2xl shadow-lg">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-blue-700 flex items-center gap-2">
            💰 Cronograma de Pagos
        </h2>
        <div class="text-right">
            <p class="text-gray-600 text-sm">Monto pendiente total</p>
            <p class="text-xl font-semibold text-red-600">
                S/. {{ number_format($totalAdeudado, 2) }}
            </p>
        </div>
    </div>

    @if($pagos->isEmpty())
        <div class="text-center py-10">
            <p class="text-gray-500 text-lg">Aún no tienes pagos registrados.</p>
        </div>
    @else
        <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-200 rounded-lg overflow-hidden">
                <thead class="bg-blue-600 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">#</th>
                        <th class="py-3 px-4 text-left">Concepto</th>
                        <th class="py-3 px-4 text-center">Monto</th>
                        <th class="py-3 px-4 text-center">Fecha de Pago</th>
                        <th class="py-3 px-4 text-center">Estado</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($pagos as $index => $pago)
                    <tr class="hover:bg-gray-50 transition">
                        <td class="py-3 px-4 text-gray-700 font-medium">{{ $index + 1 }}</td>
                        <td class="py-3 px-4">{{ $pago->concepto }}</td>
                        <td class="py-3 px-4 text-center font-semibold text-gray-800">
                            S/. {{ number_format($pago->monto, 2) }}
                        </td>
                        <td class="py-3 px-4 text-center text-gray-600">
                            {{ \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') }}
                        </td>
                        <td class="py-3 px-4 text-center">
                            @if($pago->estado == 'Pagado')
                                <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-sm font-medium">
                                    Pagado
                                </span>
                            @else
                                <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-sm font-medium">
                                    Pendiente
                                </span>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection