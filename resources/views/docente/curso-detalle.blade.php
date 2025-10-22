@extends('app')

@section('content')
<div class="main-content">
  <h1 class="titulo-pagina">{{ $curso->nombre_curso }}</h1>

  <section class="info-curso">
    <h2>📘 Información del curso</h2>
    <p><strong>Descripción:</strong> {{ $curso->descripcion ?? 'Sin descripción.' }}</p>
  </section>

  <section class="temas-semanales">
    <h2>📅 Temas por semana</h2>
    @forelse($temasPorSemana as $semana => $temas)
      <div class="semana-bloque">
        <h3>Semana {{ $semana }}</h3>
        @foreach($temas as $tema)
          <div class="tema-item">
            <h4>{{ $tema->titulo_tema }}</h4>
            <p>{{ $tema->contenido }}</p>

            @if($tema->recurso_pdf)
              <a href="{{ asset('storage/'.$tema->recurso_pdf) }}" target="_blank" class="btn">📄 Ver PDF</a>
            @endif

            @if($tema->recurso_video)
              <div class="video-container">
                <iframe width="400" height="225" src="{{ $tema->recurso_video }}" frameborder="0" allowfullscreen></iframe>
              </div>
            @endif
          </div>
        @endforeach

        @if(isset($autoevaluacionesPorSemana[$semana]))
          <div class="autoevaluacion-bloque">
            <h4>🧩 Autoevaluación</h4>
            @foreach($autoevaluacionesPorSemana[$semana] as $eval)
              <a href="{{ url('docente/autoevaluacion/'.$eval->id_autoevaluacion) }}" class="btn btn-evaluacion">
                {{ $eval->titulo }}
              </a>
            @endforeach
          </div>
        @endif
      </div>
    @empty
      <p>No hay temas registrados aún.</p>
    @endforelse
  </section>

  <a href="{{ url('docente/cursos') }}" class="btn-volver">⬅ Volver</a>
</div>

@endsection
