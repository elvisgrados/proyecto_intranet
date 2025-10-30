@extends('app')


@section('content')
<div class="asistencia-container">

    @if(session('success'))
        <div class="bg-green-100 text-green-800 p-3 rounded mb-3">
            {{ session('success') }}
        </div>
    @endif

    <h1 class="asistencia-title"><span>📅</span> Control de Asistencia</h1>

    <!-- FILTROS -->
    <form method="GET" class="asistencia-filtros">
        <div style="display:flex; gap:20px; align-items:center; flex-wrap:wrap;">
            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Curso</label>
                <select name="curso" class="border rounded-lg px-3 py-2 w-56 focus:ring-2 focus:ring-indigo-500"
                        onchange="this.form.submit()">
                    @foreach ($cursos as $curso)
                        <option value="{{ $curso->id_curso }}"
                            {{ $cursoId == $curso->id_curso ? 'selected' : '' }}>
                            {{ $curso->nombre_curso }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-600 mb-1">Fecha</label>
                <input type="date" name="fecha" value="{{ $fecha }}" 
                       class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500"
                       onchange="this.form.submit()">
            </div>
        </div>
    </form>


    <!-- TABLA -->
    <form method="POST" action="{{ route('asistencia.store') }}" class="asistencia-card">
        @csrf

        <input type="hidden" name="curso" value="{{ $cursoId }}">
        <input type="hidden" name="fecha" value="{{ $fecha }}">

        <table class="asistencia-table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Alumno</th>
                    <th class="text-center">Estado</th>
                    <th class="text-center">Observación</th>
                </tr>
            </thead>

            <tbody>
            @forelse ($alumnos as $i => $a)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $a->nombres }} {{ $a->apellidos }}</td>

                    <td class="text-center">
                        <select name="alumnos[{{ $a->id_alumno }}][estado]" class="asistencia-select">
                            <option value="Seleccionar">Seleccione</option>
                            <option value="Presente" {{ $a->estado=='Presente'?'selected':'' }}>Presente</option>
                            <option value="Ausente" {{ $a->estado=='Ausente'?'selected':'' }}>Ausente</option>
                            <option value="Tarde" {{ $a->estado=='Tarde'?'selected':'' }}>Tarde</option>
                            <option value="Justificado" {{ $a->estado=='Justificado'?'selected':'' }}>Justificado</option>
                        </select>
                    </td>

                    <td class="text-center">
                        <input type="text" 
                               name="alumnos[{{ $a->id_alumno }}][observacion]"
                               value="{{ $a->observacion }}"
                               class="asistencia-input">
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center py-3 text-gray-500">
                        No hay alumnos matriculados en este curso.
                    </td>
                </tr>
            @endforelse
            </tbody>
        </table>

        @if(count($alumnos) > 0)
        <div class="mt-6 text-right">
            <button class="asistencia-save-btn">💾 Guardar asistencia</button>
        </div>
        @endif
    </form>

</div>
@endsection
