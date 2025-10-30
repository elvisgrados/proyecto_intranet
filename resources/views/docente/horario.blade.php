@extends('app')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/horario.css') }}">
@endpush

@section('content')
<div class="horario-page">
    <div class="horario-header">
        <h1 class="horario-title">🗓️ Mi Horario de Clases</h1>
        <span class="horario-sub">{{ $periodo->nombre ?? 'Periodo no definido' }}</span>

    </div>

    <!-- Información del docente -->
    <div class="horario-card mb-6">
        <p><strong>Docente:</strong> {{ $docente->usuario->nombres }} {{ $docente->usuario->apellidos }}</p>
        <p><strong>Especialidad:</strong> {{ $docente->especialidad ?? 'No especificada' }}</p>
    </div>

    <!-- Tabla dinámica -->
    <div class="horario-card overflow-x-auto">
        <table class="horario-table">
            <thead>
                <tr>
                    <th>Día</th>
                    <th>Hora</th>
                    <th>Curso</th>
                    <th>Aula</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($horarios as $h)
                    <tr>
                        <td>{{ ucfirst($h->dia) }}</td>
                        <td>{{ $h->hora_inicio }} - {{ $h->hora_fin }}</td>
                        <td class="curso-nombre">{{ $h->curso->nombre_curso }}</td>
                        <td>{{ $h->aula ?? '—' }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="no-horarios">No hay horarios asignados.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

   
</div>
@endsection
