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

.estado.activo { background: #d9f7e4; color: #16784e; }
.estado.inactivo { background: #ffe1e1; color: #b73232; }

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

    <!-- ================= HEADER ================= -->
    <div class="matriculas-header-card">
        <h1>Gestión de Docentes</h1>
        <div class="matriculas-header-actions">
            <a href="{{ route('admin.dashboard') }}" class="btn-volver">Volver al Panel</a>
            <a href="#" class="btn-exportar" onclick="exportToExcel('docentes-body')">Exportar Excel</a>
        </div>
    </div>

    <!-- ================= FORMULARIO DOCENTE ================= -->
    <div class="form-card">
        <h2>Registrar Nuevo Docente</h2>
        <form action="{{ route('admin.docentes.agregar') }}" method="POST">
            @csrf
            <div class="form-grid">
                <div class="form-group">
                    <label>Nombre del Docente</label>
                    <input type="text" name="nombre" required>
                </div>
                <div class="form-group">
                    <label>Apellidos del Docente</label>
                    <input type="text" name="apellidos" required>
                </div>
                <div class="form-group">
                    <label>DNI</label>
                    <input type="text" name="dni" required maxlength="8">
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
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>
                <div class="form-group full">
                    <label>Cursos que dicta</label>
                    <select name="cursos[]" multiple size="5">
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id_curso }}">{{ $curso->nombre_curso }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <button type="submit" class="btn-registrar">Registrar Docente</button>
        </form>
    </div>

    <!-- ================= TABLA DOCENTES ================= -->
    <div class="tabla-card">
        <h2>Docentes Registrados</h2>
        <table class="tabla-moderna">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Teléfono</th>
                    <th>Estado</th>
                    <th>Cursos</th>
                    <th style="text-align:center;">Acciones</th>
                </tr>
            </thead>
            <tbody id="docentes-body">
                @foreach($docentes as $index => $docente)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>
                        {{ $docente->usuario->nombres ?? '' }}
                        {{ $docente->usuario->apellidos ?? '' }}
                    </td>
                    <td>{{ $docente->usuario->email }}</td>
                    <td>{{ $docente->usuario->telefono }}</td>
                    <td><span class="estado {{ strtolower($docente->estado) }}">{{ $docente->estado }}</span></td>
                    <td>
                        @foreach($docente->cursos as $curso)
                            {{ $curso->nombre_curso }}@if(!$loop->last), @endif
                        @endforeach
                    </td>
                    <td class="acciones">
                        <button class="btn-editar"
                            onclick="abrirModal('docente',
                                {{ $docente->id_docente }},
                                '{{ $docente->usuario->nombres ?? '' }} {{ $docente->usuario->apellidos ?? '' }}',
                                '{{ $docente->usuario->email }}',
                                '{{ $docente->usuario->telefono }}',
                                '{{ $docente->estado }}',
                                [@foreach($docente->cursos as $curso)'{{ $curso->nombre_curso }}'@if(!$loop->last),@endif @endforeach]
                            )">
                            <i class="fas fa-edit"></i>
                        </button>
                        <form action="{{ route('admin.docentes.eliminar', $docente->id_docente) }}" method="POST" style="display:inline;">
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

    <!-- ================= MODAL EDITAR DOCENTE ================= -->
    <div id="modalEditar" class="modal-overlay">
        <div class="modal-content">
            <h2 id="modalTitle">Editar Docente</h2>
            <form id="formEditar" method="POST">
                @csrf
                @method('PUT')
                <div class="form-grid" id="modalFields">
                    <!-- Se llena dinámicamente -->
                </div>
                <div class="modal-buttons">
                    <button type="button" class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
                    <button type="submit" class="btn-guardar">Guardar Cambios</button>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
function abrirModal(tipo, id, nombre, email, telefono, estado, cursos=[]) {
    const modalFields = document.getElementById("modalFields");
    const modalForm = document.getElementById("formEditar");
    modalForm.action = `/admin/docentes/actualizar/${id}`;
    let options = '';

    @foreach($cursos as $curso)
        options += `<option value="{{ $curso->id_curso }}" ${cursos.includes('{{ $curso->id_curso }}') ? 'selected' : ''}>{{ $curso->nombre_curso }}</option>`;
    @endforeach

    modalFields.innerHTML = `
        <div class="form-group">
            <label>Nombre del Docente</label>
            <input type="text" name="nombre" value="${nombre}">
        </div>
        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="${email}">
        </div>
        <div class="form-group">
            <label>Teléfono</label>
            <input type="text" name="telefono" value="${telefono}">
        </div>
        <div class="form-group">
            <label>Estado</label>
            <select name="estado">
                <option ${estado==='Activo'?'selected':''}>Activo</option>
                <option ${estado==='Inactivo'?'selected':''}>Inactivo</option>
            </select>
        </div>
        <div class="form-group full">
            <label>Cursos que dicta</label>
            <select name="cursos[]" multiple size="5">${options}</select>
        </div>
    `;

    document.getElementById("modalEditar").classList.add("active");
}

function cerrarModal() {
    document.getElementById("modalEditar").classList.remove("active");
}

function confirmarEliminar(event) {
    event.preventDefault();
    const form = event.target.closest('form');
    Swal.fire({
        title: `¿Eliminar docente?`,
        text: "Esta acción no se puede deshacer.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#c62828",
        cancelButtonColor: "#6c757d"
    }).then(result => {
        if (result.isConfirmed) form.submit();
    });
}

function exportToExcel(tbodyId) {
    const tableRows = document.getElementById(tbodyId).parentElement.outerHTML;
    const blob = new Blob([tableRows], { type: "application/vnd.ms-excel" });
    const url = URL.createObjectURL(blob);
    const a = document.createElement("a");
    a.href = url;
    a.download = `${tbodyId}.xls`;
    a.click();
    URL.revokeObjectURL(url);
}
</script>
@endsection
