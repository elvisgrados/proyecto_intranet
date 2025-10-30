@extends('app')


@section('content')
<div class="container">
  <div class="p-4 bg-white shadow rounded">
    <h4>👋 Hola, {{ Auth::user()->nombres ?? 'Alumno' }}</h4>
    <p>Bienvenido a tu portal académico. Desde aquí puedes revisar tu horario, cursos, pagos y tus resultados de exámenes.</p>

    <div class="row mt-4">
      <div class="col-md-4 mb-3">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <i class="fa-solid fa-calendar-days fa-2x text-primary mb-2"></i>
            <h6>Horario</h6>
            <a href="{{ route('alumno.horario') }}" class="btn btn-outline-primary btn-sm mt-2">Ver</a>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <i class="fa-solid fa-book fa-2x text-success mb-2"></i>
            <h6>Cursos</h6>
            <a href="{{ route('alumno.cursos') }}" class="btn btn-outline-success btn-sm mt-2">Ver</a>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-3">
        <div class="card text-center shadow-sm">
          <div class="card-body">
            <i class="fa-solid fa-chart-bar fa-2x text-warning mb-2"></i>
            <h6>Resultados</h6>
            <a href="{{ route('alumno.resultados') }}" class="btn btn-outline-warning btn-sm mt-2">Ver</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection