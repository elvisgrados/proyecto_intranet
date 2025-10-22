@extends('app')

@section('content')
<div class="container mt-4">
  <h2 class="titulo-pagina mb-4">
    <i class="bi bi-clipboard-check text-primary me-2"></i> Calificaciones
  </h2>

  <div class="resultado-lista">
    @forelse ($resultados as $simulacro)
      <div class="resultado-card shadow-sm p-3 mb-4 bg-white rounded-4">
        <div class="d-flex justify-content-between align-items-center border-bottom pb-2 mb-2">
          <div>
            <h5 class="mb-0 text-primary fw-bold">{{ strtoupper($simulacro->titulo) }}</h5>
            <small class="text-muted">
              📅 {{ \Carbon\Carbon::parse($simulacro->fecha)->format('d M Y') }}
            </small>
          </div>

          {{-- Puntaje dentro de la tarjeta --}}
          <div class="puntaje text-end">
            <span class="badge fs-6 px-3 py-2 text-dark"
              style="background: linear-gradient(90deg, #d9d7ff, #b3a6ff); border-radius: 12px;">
              {{ $simulacro->puntaje ?? '—' }}/20
            </span>
          </div>


        <p class="mb-2">{{ $simulacro->descripcion }}</p>

      </div>
    @empty
      <p class="text-muted text-center">No hay simulacros registrados aún.</p>
    @endforelse
  </div>


@endsection


@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('puntajeChart').getContext('2d');

new Chart(ctx, {
    type: 'bar',
    data: {
        labels: {!! json_encode($labels) !!},
        datasets: [{
            label: 'Puntaje obtenido',
            data: {!! json_encode($puntajes) !!},
            backgroundColor: 'rgba(54, 162, 235, 0.6)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: {
                beginAtZero: true,
                max: 20,
                title: {
                    display: true,
                    text: 'Puntaje'
                }
            }
        }
    }
});
</script>
@endsection
