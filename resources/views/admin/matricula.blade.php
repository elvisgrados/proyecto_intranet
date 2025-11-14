@extends('app')

@section('content')

<style>
/* ───────────────────── */
/*   CONTENEDOR GLOBAL   */
/* ───────────────────── */
.matriculas-wrapper {
    display: flex;
    flex-direction: column;
    gap: 30px;
    padding: 20px;
}

/* ───────────────────── */
/*       HEADER           */
/* ───────────────────── */
.matriculas-header-card {
    background: #fff;
    padding: 20px 25px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.matriculas-header-card h1 {
    font-size: 1.8rem;
    color: #4a2da0;
    font-weight: 700;
}

.matriculas-header-actions {
    display: flex;
    gap: 10px;
}

.btn-volver,
.btn-exportar {
    padding: 10px 16px;
    background: #6c63ff;
    color: #fff;
    border-radius: 8px;
    text-decoration: none;
    transition: .3s;
}

.btn-exportar { background: #28a745; }

.btn-volver:hover { background: #5944c2; }
.btn-exportar:hover { background: #218838; }

/* ───────────────────── */
/*     FORMULARIO        */
/* ───────────────────── */
.form-card {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.form-card h2 {
    margin-bottom: 15px;
    color: #4a2da0;
    font-size: 1.2rem;
    font-weight: 600;
}

.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 15px 20px;
}

.form-group label {
    font-weight: 600;
    font-size: 0.9rem;
}

.form-group input,
.form-group select {
    width: 100%;
    padding: 8px;
    border-radius: 8px;
    border: 1px solid #ccc;
    margin-top: 4px;
}

.form-group.full {
    grid-column: span 2;
}

.btn-registrar {
    margin-top: 18px;
    padding: 12px 18px;
    background: #4a2da0;
    border-radius: 8px;
    color: #fff;
    border: none;
    cursor: pointer;
    transition: .3s;
}

.btn-registrar:hover { background: #3a2180; }

/* ───────────────────── */
/*        TABLA          */
/* ───────────────────── */
.tabla-card {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.tabla-card h2 {
    margin-bottom: 15px;
    font-size: 1.2rem;
    font-weight: 600;
    color: #4a2da0;
}

.tabla-moderna {
    width: 100%;
    border-collapse: collapse;
}

.tabla-moderna thead {
    background: #4a2da0;
    color: #fff;
}

.tabla-moderna th,
.tabla-moderna td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

.tabla-moderna tbody tr:hover {
    background: #f5f3ff;
}

.estado {
    padding: 5px 10px;
    border-radius: 6px;
    font-weight: 600;
}

.estado.activa { background: #d9f7e4; color: #16784e; }
.estado.inactiva { background: #ffe1e1; color: #b73232; }

.acciones {
    display: flex;
    justify-content: center;
    gap: 10px;
}

.btn-editar,
.btn-eliminar {
    background: none;
    border: none;
    font-size: 18px;
    cursor: pointer;
}

.btn-editar { color: #4a2da0; }
.btn-eliminar { color: #c62828; }

.btn-editar:hover { color: #6c63ff; }
.btn-eliminar:hover { color: #ff1744; }

/* ───────────────────── */
/*        GRÁFICO        */
/* ───────────────────── */
.grafico-card {
    background: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}

.grafico-card h2 {
    margin-bottom: 10px;
    font-size: 1.2rem;
    color: #4a2da0;
    font-weight: 600;
}

.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(0,0,0,0.5);
    display: none;
    justify-content: center;
    align-items: center;
    z-index: 3000;
}

.modal-overlay.active {
    display: flex;
}

.modal-content {
    background: white;
    padding: 25px;
    width: 500px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.2);
    animation: pop .3s ease;
}

@keyframes pop {
    0% { transform: scale(.8); opacity: 0; }
    100% { transform: scale(1); opacity: 1; }
}

.modal-buttons {
    display: flex;
    justify-content: flex-end;
    gap: 15px;
    margin-top: 20px;
}

.btn-cancelar {
    padding: 10px 15px;
    background: #aaa;
    border-radius: 8px;
    color: #fff;
    border: none;
    cursor: pointer;
}

.btn-guardar {
    padding: 10px 15px;
    background: #4a2da0;
    border-radius: 8px;
    color: #fff;
    border: none;
    cursor: pointer;
}

.btn-guardar:hover { background: #3a2180; }

</style>

<div class="matriculas-wrapper">

    <!-- ✅ HEADER -->
    <div class="matriculas-header-card">
        <h1>Gestión de Matrículas</h1>

        <div class="matriculas-header-actions">
            <a href="{{ route('admin.dashboard') }}" class="btn-volver">Volver al Panel</a>
            <a href="{{ route('admin.matriculas.excel') }}" class="btn-exportar">Exportar Excel</a>
        </div>
    </div>

    <!-- ✅ FORMULARIO REGISTRO -->
    <div class="form-card">
        <h2>Registrar Nueva Matrícula</h2>

        <form action="{{ route('admin.matricula.agregar') }}" method="POST">
            @csrf
            <div class="form-grid">

                <div class="form-group">
                    <label>DNI</label>
                    <input type="text" name="dni" required>
                </div>

                <div class="form-group">
                    <label>Nombres</label>
                    <input type="text" name="nombres" required>
                </div>

                <div class="form-group">
                    <label>Apellidos</label>
                    <input type="text" name="apellidos" required>
                </div>

                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Teléfono</label>
                    <input type="text" name="telefono" required>
                </div>

                <div class="form-group">
                    <label>Contraseña</label>
                    <input type="password" name="password" required>
                </div>

                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado" required>
                        <option value="activa">Activo</option>
                        <option value="inactiva">Inactivo</option>
                    </select>
                </div>

                <div class="form-group full">
                    <label>Colegio de Procedencia</label>
                    <input type="text" name="colegio_procedencia" required>
                </div>

                <div class="form-group">
                    <label>Turno</label>
                    <select name="id_turno" required>
                        <option>Seleccione...</option>
                        @foreach($turnos as $turno)
                            <option value="{{ $turno->id_turno }}">{{ $turno->nombre_turno }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Carrera</label>
                    <select name="id_carrera" required>
                        <option>Seleccione...</option>
                        @foreach($carreras as $carrera)
                            <option value="{{ $carrera->id_carrera }}">{{ $carrera->nombre_carrera }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Horario</label>
                    <select name="id_horario" required>
                        <option>Seleccione...</option>
                        @foreach($horarios as $horario)
                            <option value="{{ $horario->id_horario }}">{{ $horario->hora_inicio }} - {{ $horario->hora_fin }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Fecha de Matrícula</label>
                    <input type="date" name="fecha_matricula" required>
                </div>

            </div>

            <button type="submit" class="btn-registrar">Registrar Matrícula</button>
        </form>
    </div>

    <!-- ✅ TABLA -->
    <div class="tabla-card">
        <h2>Matrículas Registradas</h2>
        <table class="tabla-moderna">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Alumno</th>
                    <th>Carrera</th>
                    <th>Turno</th>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th style="text-align:center;">Acciones</th>
                </tr>
            </thead>
            <tbody>

                @foreach($matriculas as $index => $matricula)
                <tr>
                    <td>{{ $index + 1 }}</td>

                    <td>
                        {{ $matricula->alumno->usuario->nombres ?? '' }}
                        {{ $matricula->alumno->usuario->apellidos ?? '' }}
                    </td>

                    <td>{{ $matricula->carrera->nombre_carrera ?? 'Sin carrera' }}</td>
                    <td>{{ $matricula->turno->nombre_turno ?? 'Sin turno'}}</td>
                    <td>{{ $matricula->fecha_matricula ?? 'Sin fecha'}}</td>

                    <td><span class="estado {{ $matricula->estado }}">{{ ucfirst($matricula->estado) }}</span></td>

                    <td class="acciones">
                        <form id="formEditarMatricula" method="POST" action="{{ route('admin.matricula.editar', $matricula->id_matricula) }}">
                            @csrf
                            <button class="btn-editar"
                            onclick="abrirModal(
                                '{{ $matricula->id_matricula }}',
                                '{{ $matricula->alumno->usuario->nombres ?? '' }} {{ $matricula->alumno->usuario->apellidos ?? '' }}',
                                '{{ $matricula->id_carrera ?? '' }}',
                                '{{ $matricula->id_turno ?? '' }}',
                                '{{ $matricula->fecha_matricula ?? '' }}',
                                '{{ $matricula->estado ?? '' }}'
                                )">
                                <i class="fas fa-edit"></i>
                            </button>
                        </form>

                        <form method="POST"
                            action="{{ route('admin.matricula.eliminar', $matricula->id_matricula) }}"
                            style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button class="btn-eliminar" onclick="confirmarEliminar(event)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach

            </tbody>
        </table>
    </div>

    <!-- ✅ GRÁFICO -->
    <div class="grafico-card">
        <h2>Matrículas por Mes</h2>
        <canvas id="graficoMatriculas"></canvas>
    </div>

</div>

<!-- ✅ MODAL EDITAR -->
<div id="modalEditar" class="modal-overlay">
    <div class="modal-content">

        <h2>Editar Matrícula</h2>

        <form id="formEditar" method="POST" onsubmit="guardarCambios(event)">
    @csrf
    <div class="form-grid">
        <div class="form-group">
            <label>Alumno</label>
            <input type="text" id="editAlumno" disabled>
        </div>

        <div class="form-group">
            <label>Carrera</label>
            <select id="editCarrera" name="id_carrera">
                @foreach($carreras as $carrera)
                    <option value="{{ $carrera->id_carrera }}">{{ $carrera->nombre_carrera }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Turno</label>
            <select id="editTurno" name="id_turno">
                @foreach($turnos as $turno)
                    <option value="{{ $turno->id_turno }}">{{ $turno->nombre_turno }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Fecha Matrícula</label>
            <input type="date" id="editFecha" name="fecha_matricula">
        </div>

        <div class="form-group">
            <label>Estado</label>
            <select id="editEstado" name="estado">
                <option value="activa">Activo</option>
                <option value="inactiva">Inactivo</option>
            </select>
        </div>
    </div>

    <div class="modal-buttons">
        <button type="button" class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
        <button type="submit" class="btn-guardar">Guardar Cambios</button>
    </div>
</form>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
// ✅ abrir modal
function abrirModal(id, alumno, idCarrera, idTurno, fecha, estado) {
    document.getElementById("editAlumno").value = alumno;
    document.getElementById("editCarrera").value = idCarrera;
    document.getElementById("editTurno").value = idTurno;
    document.getElementById("editFecha").value = fecha;
    document.getElementById("editEstado").value = estado;

    // ✅ asignar la URL dinámica del formulario
    document.getElementById("formEditar").action = "/admin/matricula/editar/" + id;

    // ✅ abrir modal
    document.getElementById("modalEditar").classList.add("active");
}

// ✅ guardar cambios
function guardarCambios(event) {
    event.preventDefault();
    const form = event.target;
    const url = form.action;

    Swal.fire({
        title: '¿Guardar cambios?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, guardar',
        cancelButtonText: 'Cancelar',
        confirmButtonColor: '#4a2da0',
        cancelButtonColor: '#aaa',
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(url, {
                method: 'POST',
                body: new FormData(form),
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'Cambios guardados',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    cerrarModal();
                    setTimeout(() => location.reload(), 1500);
                } else {
                    Swal.fire('Error', 'No se pudieron guardar los cambios.', 'error');
                }
            })
            .catch(() => Swal.fire('Error', 'Ocurrió un error al actualizar.', 'error'));
        }
    });
}

// ✅ cerrar modal
function cerrarModal() {
    document.getElementById("modalEditar").classList.remove("active");
}

// ✅ confirmar eliminación con SweetAlert2
function confirmarEliminar(event) {
    event.preventDefault();
    const form = event.target.closest('form');

    Swal.fire({
        title: '¿Eliminar matrícula?',
        text: "Esta acción no se puede deshacer.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#4a2da0',
        cancelButtonColor: '#aaa',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            fetch(form.action, {
                method: 'POST',
                body: new FormData(form)
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    Swal.fire({
                        title: 'Eliminado',
                        text: 'La matrícula fue eliminada correctamente.',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                    setTimeout(() => location.reload(), 1500);
                }
            })
            .catch(() => {
                Swal.fire('Error', 'No se pudo eliminar la matrícula.', 'error');
            });
        }
    });
}

// ✅ gráfico
const ctx = document.getElementById('graficoMatriculas').getContext('2d');
new Chart(ctx, {
    type: 'bar',
    data: {
        labels: @json($labels),
        datasets: [{
            label: 'Matrículas',
            data: @json($data),
            backgroundColor: 'rgba(75, 123, 236, 0.7)',
            borderColor: 'rgba(75, 123, 236, 1)',
            borderWidth: 1
        }]
    }
});
</script>


@endsection
