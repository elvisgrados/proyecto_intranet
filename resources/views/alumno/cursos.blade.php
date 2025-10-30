@extends('app')

@section('content')
<div class="p-4 bg-white shadow rounded">
  <h4>📘 Mis Cursos</h4>
  <div class="row mt-3">
    @foreach($cursos as $curso)
    <div class="col-md-4 mb-3">
      <div class="card border-0 shadow-sm">
        <div class="card-body">
          <h6 class="fw-bold">{{ $curso->nombre_curso }}</h6>
          <p><strong>Docente:</strong> {{ $curso->docente }}</p>
          <a href="{{ route('alumno.curso_detalle', $curso->id_curso) }}" class="btn btn-outline-primary btn-sm">
          Ver detalles
          </a>

        </div>
      </div>
    </div>
    @endforeach
  </div>
</div>
@endsection