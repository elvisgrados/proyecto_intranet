@extends('app')

@section('content')
<div class="container">
    <h2>Mi Horario</h2>

    <table class="table">
        <thead>
            <tr>
                <th>Día</th>
                <th>Hora</th>
                <th>Curso</th>
                <th>Aula</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Lunes</td>
                <td>8:00 - 10:00</td>
                <td>Matemática</td>
                <td>Aula 101</td>
            </tr>
            <tr>
                <td>Miércoles</td>
                <td>10:00 - 12:00</td>
                <td>Física</td>
                <td>Aula 202</td>
            </tr>
        </tbody>
    </table>
</div>
@endsection
