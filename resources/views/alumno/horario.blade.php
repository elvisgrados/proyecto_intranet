@extends('app')

@section('content')

<div class="container mt-4">
  <h4>📅 Horario</h4>
  <table class="table table-bordered mt-3">
    <thead>
      <tr>
        <th>Día</th>
        <th>Hora inicio</th>
        <th>Hora fin</th>
        <th>Curso</th>
        <th>Aula</th>
        <th>Docente</th>
      </tr>
    </thead>
    <tbody>
      @foreach($horarios as $h)
      <tr>
        <td>{{ $h->dia }}</td>
        <td>{{ $h->hora_inicio }}</td>
        <td>{{ $h->hora_fin }}</td>
        <td>{{ $h->nombre_curso }}</td>
        <td>{{ $h->aula }}</td>
        <td>{{ $h->docente }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection