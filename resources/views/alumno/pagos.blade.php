@extends('app')

@section('content')
<div class="main-content">
  <h1 class="titulo-pagina">💳 Estado de Cuenta</h1>

  <!-- 📊 Resumen de Totales -->
  <div class="resumen-grid">
    <div class="resumen-card bg-primary text-white">
      <div class="resumen-titulo">Total a Pagar</div>
      <div class="resumen-valor">S/ {{ number_format($total, 2) }}</div>
    </div>

    <div class="resumen-card bg-success text-white">
      <div class="resumen-titulo">Total Pagado</div>
      <div class="resumen-valor">S/ {{ number_format($totalPagado, 2) }}</div>
    </div>

    <div class="resumen-card bg-danger text-white">
      <div class="resumen-titulo">Pendiente</div>
      <div class="resumen-valor">S/ {{ number_format($totalPendiente, 2) }}</div>
    </div>
  </div>

  <!-- 📅 Cronograma de Pagos -->
  <h2 class="subtitulo mt-5"></h2>

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Concepto</th>
          <th>Fecha de Vencimiento</th>
          <th>Monto (S/)</th>
          <th>Fecha de Pago</th>
          <th>Estado</th>
        </tr>
      </thead>
      <tbody>
        @forelse($pagos as $pago)
        <tr>
          <td>{{ $loop->iteration }}</td>
          <td>{{ $pago->concepto }}</td>
          <td>{{ \Carbon\Carbon::parse($pago->fecha_vencimiento)->format('d/m/Y') }}</td>
          <td>{{ number_format($pago->monto, 2) }}</td>
          <td>
            {{ $pago->fecha_pago ? \Carbon\Carbon::parse($pago->fecha_pago)->format('d/m/Y') : '—' }}
          </td>
          <td>
            @if($pago->estado == 'Pagado')
              <span class="badge bg-success">Pagado</span>
            @elseif($pago->estado == 'Pendiente')
              <span class="badge bg-warning text-dark">Pendiente</span>
            @else
              <span class="badge bg-danger">Vencido</span>
            @endif
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="6" class="text-center text-muted">No hay registros de pagos disponibles.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection

