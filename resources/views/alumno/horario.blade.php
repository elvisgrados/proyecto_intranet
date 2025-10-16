@extends('app')

@section('content')
<div class="container mt-4">

        <div class="d-flex align-items-center justify-content-between mb-3">
        <h3 class="fw-bold text-primary mb-0">📅 Mis Clases</h3>
        <input type="date" id="fecha" class="form-control w-auto">
        </div>



    @forelse($cursos as $curso)
        <div class="card mb-3 shadow-sm border-0">
            <div class="row g-0 align-items-center">
                {{-- Columna fecha --}}
                <div class="col-3 col-md-2 text-center bg-light py-3">
                    @if($curso->dia)
                        <div class="fw-bold fs-4 text-primary">{{ date('d', strtotime($curso->dia)) }}</div>
                        <div class="text-uppercase small text-secondary">
                            {{ \Carbon\Carbon::parse($curso->dia)->translatedFormat('D') }}
                        </div>
                    @else
                        <div class="fw-bold fs-4 text-secondary">--</div>
                        <div class="small text-secondary">Sin día</div>
                    @endif
                </div>

                {{-- Columna info clase --}}
                <div class="col-9 col-md-10 p-3">
                    <p class="fw-semibold mb-1 text-dark">
                        {{ date('g:i A', strtotime($curso->hora_inicio)) }} - {{ date('g:i A', strtotime($curso->hora_fin)) }}
                    </p>
                    <h5 class="fw-bold text-uppercase text-primary mb-1">{{ $curso->nombre_curso }}</h5>
                    <p class="text-muted mb-0">
                        {{ $curso->nombre_docente ?? 'Docente no asignado' }}
                    </p>
                    <p class="text-muted small mb-0">
                        {{ $curso->aula ?? 'Sin aula' }}
                    </p>
                </div>
            </div>
        </div>
    @empty
        <div class="alert alert-info text-center mt-4">
            No tienes horarios asignados actualmente.
        </div>
    @endforelse
</div>
@endsection


<script>
document.addEventListener("DOMContentLoaded", () => {
    const inputFecha = document.getElementById("fecha");
    const contenedor = document.querySelector("#lista-cursos");
    const mesActual = document.getElementById("mes-actual");

    // ✅ Cambiar mes automáticamente al seleccionar una fecha
    inputFecha.addEventListener("change", function() {
        const fecha = new Date(this.value);

        // Mostrar el mes y año elegidos en español
        const opciones = { month: "long", year: "numeric" };
        const mesFormateado = fecha.toLocaleDateString("es-ES", opciones);

        mesActual.textContent = mesFormateado.charAt(0).toUpperCase() + mesFormateado.slice(1);

        // 🔎 Hacer la petición al backend
        fetch(`/alumno/horario/filtrar?fecha=${this.value}`)
            .then(response => response.json())
            .then(data => {
                contenedor.innerHTML = "";

                if (data.length === 0) {
                    contenedor.innerHTML = '<div class="alert alert-info text-center mt-4">No hay clases para esta fecha.</div>';
                    return;
                }

                data.forEach(curso => {
                    contenedor.innerHTML += `
                        <div class="card mb-3 shadow-sm border-0">
                            <div class="row g-0 align-items-center">
                                <div class="col-3 col-md-2 text-center bg-light py-3">
                                    <div class="fw-bold fs-4 text-primary">
                                        ${new Date(curso.dia).getDate().toString().padStart(2, '0')}
                                    </div>
                                    <div class="small text-secondary">
                                        ${new Date(curso.dia).toLocaleDateString('es-ES', { weekday: 'short' }).toUpperCase()}
                                    </div>
                                </div>
                                <div class="col-9 col-md-10 p-3">
                                    <h5 class="mb-1 text-primary fw-bold">${curso.nombre_curso}</h5>
                                    <p class="mb-0 text-secondary">${curso.nombre_docente || 'Sin docente'}</p>
                                    <small class="text-muted">${curso.hora_inicio || '--'} - ${curso.hora_fin || '--'} | Aula: ${curso.aula || 'Sin aula'}</small>
                                </div>
                            </div>
                        </div>
                    `;
                });
            })
            .catch(error => console.error("Error al filtrar:", error));
    });
});
</script>
