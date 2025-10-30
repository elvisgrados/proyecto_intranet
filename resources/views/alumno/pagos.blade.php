@extends('app')

@section('content')
<div class="p-4 bg-white shadow rounded">
  <h4>💰 Cronograma de Pagos</h4>
  <p class="mt-2">Monto pendiente total: <strong>S/. {{ number_format($totalAdeudado, 2) }}</strong></p>

  <table class="table table-hover mt-3">
    <thead>
      <tr>
        <th>Concepto</th>
        <th>Monto</th>
        <th>Fecha</th>
        <th>Estado</th>
      </tr>
    </thead>
    <tbody>
      @foreach($pagos as $pago)
      <tr>
        <td>{{ $pago->concepto }}</td>
        <td>S/. {{ number_format($pago->monto, 2) }}</td>
        <td>{{ $pago->fecha_pago }}</td>
        <td>
          <span class="badge bg-{{ $pago->estado == 'Pagado' ? 'success' : 'danger' }}">
            {{ $pago->estado }}
          </span>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection