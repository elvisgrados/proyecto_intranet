@extends('app')

@section('content')
<style>
/* ───────────────────── */
/*   CONTENEDOR GLOBAL   */
/* ───────────────────── */
.transacciones-wrapper {
    display: flex;
    flex-direction: column;
    gap: 30px;
    padding: 20px;
}

/* ───────────────────── */
/*       HEADER          */
/* ───────────────────── */
.transacciones-header-card {
    background: #fff;
    padding: 20px 25px;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.transacciones-header-card h1 {
    font-size: 1.8rem;
    color: #4a2da0;
    font-weight: 700;
}

.transacciones-header-actions {
    display: flex;
    gap: 10px;
}

.btn-volver,
.btn-exportar {
    padding: 10px 16px;
    border-radius: 8px;
    color: #fff;
    text-decoration: none;
    transition: .3s;
    display: inline-block;
}

.btn-volver { background: #6c63ff; }
.btn-exportar { background: #28a745; }

.btn-volver:hover { background: #5944c2; }
.btn-exportar:hover { background: #218838; }

/* ───────────────────── */
/*       PESTAÑAS        */
/* ───────────────────── */
.transacciones-tabs {
    display: flex;
    gap: 10px;
    margin-bottom: 15px;
}

.tab-link {
    padding: 10px 20px;
    background: #eee;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: .3s;
    border: none;
}

.tab-link.active {
    background: #4a2da0;
    color: #fff;
}

/* ───────────────────── */
/*        TAB CONTENT    */
/* ───────────────────── */
.tab-content {
    display: none;
}

.tab-content.active {
    display: block;
}

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

/* ───────────────────── */
/*        ESTADOS        */
/* ───────────────────── */
.estado {
    padding: 5px 10px;
    border-radius: 6px;
    font-weight: 600;
}

.estado.activo { background: #d9f7e4; color: #16784e; }
.estado.pendiente { background: #ffe1e1; color: #b73232; }

/* ───────────────────── */
/*        ACCIONES       */
/* ───────────────────── */
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
    width: 450px;
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

/* ───────────────────── */
/* FORMULARIOS EN MODAL   */
/* ───────────────────── */
.form-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
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
</style>

<div class="transacciones-wrapper">

    <!-- HEADER -->
    <div class="transacciones-header-card">
        <h1>Gestión de Transacciones</h1>
        <div class="transacciones-header-actions">
            <a href="#" class="btn-volver">Volver al Panel</a>
            <a href="#" class="btn-exportar">Exportar Excel</a>
        </div>
    </div>

    <!-- PESTAÑAS -->
    <div class="transacciones-tabs">
        <button class="tab-link active" onclick="abrirTab(event, 'pagos-matriculas')">Pagos de Matrículas</button>
        <button class="tab-link" onclick="abrirTab(event, 'pagos-docentes')">Pagos a Docentes</button>
    </div>

    <!-- TAB PAGOS MATRÍCULAS -->
    <div id="pagos-matriculas" class="tab-content active">
        <div class="tabla-card">
            <h2>Pagos de Matrículas</h2>
            <table class="tabla-moderna">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Alumno</th>
                        <th>Concepto</th>
                        <th>Monto</th>
                        <th>Fecha Pago</th>
                        <th>Estado</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Carlos Díaz</td>
                        <td>Matrícula 2025</td>
                        <td>S/.1200</td>
                        <td>2025-11-09</td>
                        <td><span class="estado activo">Pagado</span></td>
                        <td class="acciones">
                            <button class="btn-editar" onclick="abrirModal('matricula',1)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-eliminar" onclick="confirmarEliminar()">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Lucia Rojas</td>
                        <td>Matrícula 2025</td>
                        <td>S/.1000</td>
                        <td>2025-11-07</td>
                        <td><span class="estado pendiente">Pendiente</span></td>
                        <td class="acciones">
                            <button class="btn-editar" onclick="abrirModal('matricula',2)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-eliminar" onclick="confirmarEliminar()">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- TAB PAGOS DOCENTES -->
    <div id="pagos-docentes" class="tab-content">
        <div class="tabla-card">
            <h2>Pagos a Docentes</h2>
            <table class="tabla-moderna">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Docente</th>
                        <th>Curso</th>
                        <th>Monto</th>
                        <th>Fecha Pago</th>
                        <th>Estado</th>
                        <th style="text-align: center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>Juan Pérez</td>
                        <td>Matemática</td>
                        <td>S/.1500</td>
                        <td>2025-11-08</td>
                        <td><span class="estado activo">Pagado</span></td>
                        <td class="acciones">
                            <button class="btn-editar" onclick="abrirModal('docente',1)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-eliminar" onclick="confirmarEliminar()">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>Ana Torres</td>
                        <td>Derecho</td>
                        <td>S/.1200</td>
                        <td>2025-11-09</td>
                        <td><span class="estado pendiente">Pendiente</span></td>
                        <td class="acciones">
                            <button class="btn-editar" onclick="abrirModal('docente',2)">
                                <i class="fas fa-edit"></i>
                            </button>
                            <button class="btn-eliminar" onclick="confirmarEliminar()">
                                <i class="fas fa-trash"></i>
                            </button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- MODAL EDITAR PAGO -->
    <div id="modalEditar" class="modal-overlay">
        <div class="modal-content">
            <h2>Editar Pago</h2>
            <form id="formEditarPago">
                <div class="form-grid">
                    <div class="form-group">
                        <label>Nombre</label>
                        <input type="text" id="editNombre">
                    </div>
                    <div class="form-group">
                        <label>Monto</label>
                        <input type="number" id="editMonto">
                    </div>
                    <div class="form-group">
                        <label>Fecha Pago</label>
                        <input type="date" id="editFecha">
                    </div>
                    <div class="form-group">
                        <label>Estado</label>
                        <select id="editEstado">
                            <option value="Pagado">Pagado</option>
                            <option value="Pendiente">Pendiente</option>
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

</div>

<script>
function abrirTab(evt, tabName) {
    // Oculta todos los tab-content
    let tabContents = document.querySelectorAll(".tab-content");
    tabContents.forEach(tc => tc.classList.remove("active"));

    // Quita active de todos los links
    let tabLinks = document.querySelectorAll(".tab-link");
    tabLinks.forEach(tl => tl.classList.remove("active"));

    // Muestra el seleccionado
    document.getElementById(tabName).classList.add("active");
    evt.currentTarget.classList.add("active");
}

function abrirModal(tipo, id) {
    // Aquí puedes cargar datos según el tipo y id
    document.getElementById("modalEditar").classList.add("active");
}

function cerrarModal() {
    document.getElementById("modalEditar").classList.remove("active");
}

function confirmarEliminar() {
    Swal.fire({
        title: "¿Eliminar pago?",
        text: "Esta acción no se puede deshacer.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar",
        confirmButtonColor: "#c62828",
        cancelButtonColor: "#6c757d"
    });
}
</script>


@endsection
