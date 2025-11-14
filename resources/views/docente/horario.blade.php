@extends('app')

@section('content')
<div class="w-full px-6 py-6">

    <!-- HEADER -->
    <div class="text-center mb-6">
        <h1 class="text-3xl font-bold text-gray-800">Horario de Clases</h1>
        <p class="text-gray-600 text-sm mt-1">{{ $periodo->nombre ?? 'Periodo no definido' }}</p>
    </div>

    <!-- INFORMACIÓN DEL DOCENTE -->
    <div class="bg-white shadow-md rounded-lg p-5 mb-6">
        <p class="text-gray-800 text-lg">
            <span class="font-semibold">Docente:</span> 
            {{ $docente->usuario->nombres }} {{ $docente->usuario->apellidos }}
        </p>

        <p class="text-gray-800 text-lg mt-1">
            <span class="font-semibold">Especialidad:</span> 
            @if($cursos->isEmpty())
                <p class="text-gray-500">No tiene cursos asignados.</p>
            @else
                    @foreach($cursos as $curso)
                             {{ $curso->nombre_curso }}
                    @endforeach
            @endif
        </p>
    </div>

    <!-- FILTRO POR DÍA -->
    <div class="bg-white shadow-md rounded-lg p-4 mb-4 flex items-center gap-3">
        <label class="text-gray-700 font-semibold">Filtrar por día:</label>
        <select id="filtroDia" class="border border-gray-300 rounded px-3 py-2 focus:ring focus:ring-blue-200">
            <option value="todos">Todos</option>
            <option value="lunes">Lunes</option>
            <option value="martes">Martes</option>
            <option value="miercoles">Miércoles</option>
            <option value="jueves">Jueves</option>
            <option value="viernes">Viernes</option>
            <option value="sabado">Sábado</option>
            <option value="domingo">Domingo</option>
        </select>
    </div>

    <!-- TABLA DINÁMICA -->
    <div class="bg-white shadow-md rounded-lg overflow-x-auto p-4">
        <table class="w-full border-collapse" id="tablaHorario">
            <thead>
                <tr class="bg-gray-100 text-gray-600 text-sm uppercase">
                    <th class="py-3 px-4 text-left">Día</th>
                    <th class="py-3 px-4 text-left">Hora</th>
                    <th class="py-3 px-4 text-left">Curso</th>
                    <th class="py-3 px-4 text-left">Aula</th>
                </tr>
            </thead>
            <tbody class="text-gray-700">
                @forelse ($horarios as $h)
                    <tr class="border-b hover:bg-gray-50 transition fila-dia" data-dia="{{ strtolower($h->dia) }}">
                        <td class="py-3 px-4 capitalize">{{ $h->dia }}</td>
                        <td class="py-3 px-4">{{ $h->hora_inicio }} - {{ $h->hora_fin }}</td>

                        <!-- BADGE CURSO -->
                        <td class="py-3 px-4">
                            <span class="px-3 py-1 font-semibold ">
                                {{ $h->curso->nombre_curso }}
                            </span>
                        </td>

                        <!-- BADGE AULA -->
                        <td class="py-3 px-4">
                            <span class="px-3 py-1 rounded-full text-xs font-semibold 
                                {{ $h->aula ? 'bg-green-600 text-white' : 'bg-gray-400 text-white' }}">
                                {{ $h->aula ?? '—' }}
                            </span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="py-4 text-center text-gray-500">No hay horarios asignados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

</div>

<script>
    document.getElementById('filtroDia').addEventListener('change', function () {
        let diaSeleccionado = this.value;
        let filas = document.querySelectorAll('.fila-dia');

        filas.forEach(fila => {
            fila.style.display = (diaSeleccionado === 'todos' || fila.dataset.dia === diaSeleccionado)
                ? ''
                : 'none';
        });
    });
</script>
@endsection
