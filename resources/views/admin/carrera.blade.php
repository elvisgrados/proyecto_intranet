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
/*        MODAL          */
/* ───────────────────── */
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

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<div class="matriculas-wrapper">

    <!-- ✅ HEADER -->
    <div class="matriculas-header-card">
        <h1>Gestión de Carreras y Cursos</h1>
        <div class="matriculas-header-actions">
            <a href="{{ route('admin.dashboard') }}" class="btn-volver">Volver al Panel</a>
        </div>
    </div>

    <!-- ✅ FORMULARIO CARRERA -->
    <div class="form-card">
        <h2>Registrar Nueva Carrera</h2>
        <form action="{{ route('admin.carreras.agregar') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre de Carrera</label>
                    <input type="text" name="nombre_carrera" required>
                </div>
                <div class="form-group">
                    <label>Área</label>
                    <input type="text" name="area" required>
                </div>
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado" required>
                        <option value="activa">Activa</option>
                        <option value="inactiva">Inactiva</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-registrar">Registrar Carrera</button>
        </form>
    </div>

    <!-- ✅ TABLA CARRERAS -->
    <div class="tabla-card">
        <h2>Carreras Registradas</h2>
        <table class="tabla-moderna">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Área</th>
                    <th>Estado</th>
                    <th style="text-align:center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($carreras as $index => $carrera)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $carrera->nombre_carrera }}</td>
                    <td>{{ $carrera->area }}</td>
                    <td><span class="estado {{ $carrera->estado }}">{{ ucfirst($carrera->estado) }}</span></td>
                    <td class="acciones">
                        <button class="btn-editar"
                            onclick="abrirModalCarrera('{{ $carrera->id_carrera }}', '{{ $carrera->nombre_carrera }}', '{{ $carrera->area }}', '{{ $carrera->estado }}')">
                            <i class='fas fa-edit'></i>
                        </button>

                        <form action="{{ route('admin.carreras.eliminar', $carrera->id_carrera) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-eliminar" onclick="return confirmarEliminar(event)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <!-- ✅ FORMULARIO CURSO -->
    <div class="form-card">
        <h2>Registrar Nuevo Curso</h2>
        <form action="{{ route('admin.cursos.agregar') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre del Curso</label>
                    <input type="text" name="nombre_curso" required>
                </div>
                <div class="form-group">
                    <label>Categoría</label>
                    <input type="text" name="categoria" required>
                </div>
                <div class="form-group">
                    <label>Duración</label>
                    <input type="text" name="duracion" required>
                </div>
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado" required>
                        <option value="activa">Activa</option>
                        <option value="inactiva">Inactiva</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-registrar">Registrar Curso</button>
        </form>
    </div>

    <!-- ✅ TABLA CURSOS -->
    <div class="tabla-card">
        <h2>Cursos Registrados</h2>
        <table class="tabla-moderna">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Categoría</th>
                    <th>Duración</th>
                    <th>Estado</th>
                    <th style="text-align:center;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($cursos as $index => $curso)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $curso->nombre_curso }}</td>
                    <td>{{ $curso->categoria }}</td>
                    <td>{{ $curso->duracion }}</td>
                    <td><span class="estado {{ $curso->estado }}">{{ ucfirst($curso->estado) }}</span></td>
                    <td class="acciones">
                        <button class="btn-editar"
                            onclick="abrirModalCurso('{{ $curso->id_curso }}', '{{ $curso->nombre_curso }}', '{{ $curso->categoria }}', '{{ $curso->duracion }}', '{{ $curso->estado }}')">
                            <i class='fas fa-edit'></i>
                        </button>

                        <form action="{{ route('admin.cursos.eliminar', $curso->id_curso) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn-eliminar" onclick="return confirmarEliminar(event)">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- ✅ MODAL EDITAR CARRERA -->
<div id="modalCarrera" class="modal-overlay">
    <div class="modal-content">
        <h2>Editar Carrera</h2>
        <form id="formEditarCarrera" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre de Carrera</label>
                    <input type="text" name="nombre_carrera" id="editNombreCarrera" required>
                </div>
                <div class="form-group">
                    <label>Área</label>
                    <input type="text" name="area" id="editAreaCarrera" required>
                </div>
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado" id="editEstadoCarrera">
                        <option value="activa">Activa</option>
                        <option value="inactiva">Inactiva</option>
                    </select>
                </div>
            </div>
            <div class="modal-buttons">
                <button type="button" class="btn-cancelar" onclick="cerrarModalCarrera()">Cancelar</button>
                <button type="submit" class="btn-guardar">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<!-- ✅ MODAL EDITAR CURSO -->
<div id="modalCurso" class="modal-overlay">
    <div class="modal-content">
        <h2>Editar Curso</h2>
        <form id="formEditarCurso" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre del Curso</label>
                    <input type="text" name="nombre_curso" id="editNombreCurso" required>
                </div>
                <div class="form-group">
                    <label>Categoría</label>
                    <input type="text" name="categoria" id="editCategoriaCurso" required>
                </div>
                <div class="form-group">
                    <label>Duración</label>
                    <input type="text" name="duracion" id="editDuracionCurso" required>
                </div>
                <div class="form-group">
                    <label>Estado</label>
                    <select name="estado" id="editEstadoCurso">
                        <option value="activa">Activa</option>
                        <option value="inactiva">Inactiva</option>
                    </select>
                </div>
            </div>
            <div class="modal-buttons">
                <button type="button" class="btn-cancelar" onclick="cerrarModalCurso()">Cancelar</button>
                <button type="submit" class="btn-guardar">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<script>
function abrirModalCarrera(id, nombre, area, estado) {
    const form = document.getElementById("formEditarCarrera");
    form.action = `/admin/carrera/actualizar/${id}`;
    document.getElementById("editNombreCarrera").value = nombre;
    document.getElementById("editAreaCarrera").value = area;
    document.getElementById("editEstadoCarrera").value = estado;
    document.getElementById("modalCarrera").classList.add("active");
}
function cerrarModalCarrera() {
    document.getElementById("modalCarrera").classList.remove("active");
}

function abrirModalCurso(id, nombre, categoria, duracion, estado) {
    const form = document.getElementById("formEditarCurso");
    form.action = `/admin/curso/actualizar/${id}`;
    document.getElementById("editNombreCurso").value = nombre;
    document.getElementById("editCategoriaCurso").value = categoria;
    document.getElementById("editDuracionCurso").value = duracion;
    document.getElementById("editEstadoCurso").value = estado;
    document.getElementById("modalCurso").classList.add("active");
}
function cerrarModalCurso() {
    document.getElementById("modalCurso").classList.remove("active");
}

function confirmarEliminar(event) {
    event.preventDefault();
    const form = event.target.closest('form');
    Swal.fire({
        title: '¿Eliminar registro?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#c62828',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(result => {
        if (result.isConfirmed) form.submit();
    });
}
</script>

@endsection
