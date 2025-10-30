@extends('app')

@section('content')
<div class="p-4 bg-white shadow rounded">
  <h4 class="mb-4 flex items-center">
    📊 <span class="ml-2">Resultados de Exámenes</span>
  </h4>

  {{-- === GRÁFICO DE BARRAS === --}}
  <div class="bg-gray-50 p-4 rounded-lg mb-5">
    <canvas id="graficoResultados" height="100"></canvas>
  </div>

  {{-- === LISTA DE RESULTADOS === --}}
  <div class="mt-4">
    <h5 class="mb-3">📋 Detalle de exámenes</h5>
    @if($resultados->isEmpty())
      <p class="text-center text-muted">Aún no tienes resultados registrados.</p>
    @else
    <table class="table table-hover align-middle text-center">
      <thead class="table-light">
        <tr>
          <th>Examen</th>
          <th>Puntaje</th>
          <th>Fecha</th>
          <th>Rendimiento</th>
        </tr>
      </thead>
      <tbody>
        @foreach($resultados as $res)
          <tr>
            <td>{{ $res->titulo }}</td>
            <td><strong>{{ $res->puntaje_total }}</strong></td>
            <td>{{ \Carbon\Carbon::parse($res->fecha_registro)->format('d/m/Y') }}</td>
            <td>
              @php
                $color = $res->puntaje_total >= 14 ? 'success' : ($res->puntaje_total >= 10 ? 'warning' : 'danger');
                $texto = $res->puntaje_total >= 14 ? 'Excelente' : ($res->puntaje_total >= 10 ? 'Regular' : 'Bajo');
              @endphp
              <span class="badge bg-{{ $color }}">{{ $texto }}</span>
            </td>
          </tr>
        @endforeach
      </tbody>
    </table>
    @endif
  </div>
</div>

{{-- === SCRIPT DE GRÁFICO === --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
  const ctx = document.getElementById('graficoResultados').getContext('2d');

  const examenes = @json($resultados->pluck('titulo'));
  const puntajes = @json($resultados->pluck('puntaje_total'));

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: examenes,
      datasets: [{
        label: 'Puntaje total',
        data: puntajes,
        backgroundColor: 'rgba(104, 54, 255, 0.7)',
        borderColor: 'rgba(104, 54, 255, 1)',
        borderWidth: 1,
        borderRadius: 8
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
          max: 20,
          ticks: { stepSize: 2 }
        }
      },
      plugins: {
        legend: { position: 'top' },
        title: {
          display: true,
          text: 'Evolución de tus puntajes',
          font: { size: 16 }
        }
      }
    }
  });
});
</script>
@endsection