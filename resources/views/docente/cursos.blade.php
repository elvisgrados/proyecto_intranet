@extends('app')

@section('content')
<div class="main-content">
  <h1 class="titulo-pagina">📚 Mis Cursos</h1>

  <div class="resumen-grid">
    <div class="resumen-card">
      <div class="resumen-titulo">Cursos Activos</div>
      <div class="resumen-valor">{{ $asignaciones->count() }}</div>
    </div>

    <div class="resumen-card">
      <div class="resumen-titulo">Total de alumnos</div>
      <div class="resumen-valor">
        {{ $asignaciones->flatMap(fn($a) => $a->curso->alumnos)->unique('id_alumno')->count() }}
      </div>
    </div>

    <div class="resumen-card">
      <div class="resumen-titulo">Evaluaciones pendientes</div>
      <div class="resumen-valor text-rojo">6</div>
    </div>
  </div>

  <div class="cursos-contenedor">
    <h2 class="cursos-titulo">Cursos asignados</h2>

    <div class="cursos-lista">
      @foreach($asignaciones as $asignacion)
        <div class="curso-item">
          <div>
            <h3 class="curso-nombre">{{ $asignacion->curso->nombre_curso }}</h3>

            <p class="curso-horario">
              @foreach($asignacion->curso->horarios as $horario)
                {{ $horario->dia }} · {{ $horario->hora_inicio }} - {{ $horario->hora_fin }}<br>
              @endforeach
            </p>
          </div>

          <div class="botones-curso">
            <a href="#" class="btn-ver">Ver</a>
            <button class="btn-reporte">Reporte</button>
          </div>
        </div>
      @endforeach
    </div>
  </div>
</div>
@endsection
