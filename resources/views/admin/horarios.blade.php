@extends('app')

@section('content')
<style>
/* === ESTILOS IGUALES A LOS TUYOS (sin cambios) === */
.horario-wrapper { display:flex; flex-direction:column; gap:30px; padding:20px; }
.horario-header-card { background:#fff; padding:20px 25px; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.08); display:flex; justify-content:space-between; align-items:center; }
.horario-header-card h1 { font-size:1.8rem; color:#4a2da0; font-weight:700; }
.horario-header-actions { display:flex; gap:10px; }
.btn-volver, .btn-exportar, .btn-agregar { padding:10px 16px; border-radius:8px; text-decoration:none; color:#fff; transition:.3s; }
.btn-volver { background:#6c63ff; } .btn-exportar { background:#28a745; } .btn-agregar { background:#007bff; }
.btn-volver:hover { background:#5944c2; } .btn-exportar:hover { background:#218838; } .btn-agregar:hover { background:#0056b3; }

.horario-tabs { display:flex; gap:10px; margin-bottom:20px; }
.tab-btn { padding:10px 18px; border:none; border-radius:8px; background:#eee; cursor:pointer; font-weight:600; color:#4a2da0; transition:.3s; }
.tab-btn.active { background:#4a2da0; color:#fff; }
.tab-btn:hover { background:#6c63ff; color:#fff; }

.tabla-card { background:#fff; padding:25px; border-radius:12px; box-shadow:0 2px 8px rgba(0,0,0,0.08); }
.tabla-moderna { width:100%; border-collapse:collapse; }
.tabla-moderna thead { background:#4a2da0; color:#fff; }
.tabla-moderna th, .tabla-moderna td { padding:12px; border-bottom:1px solid #eee; }
.tabla-moderna tbody tr:hover { background:#f5f3ff; }
.estado { padding:5px 10px; border-radius:6px; font-weight:600; }
.estado.Activo { background:#d9f7e4; color:#16784e; }
.estado.Inactivo { background:#ffe1e1; color:#b73232; }
.acciones { display:flex; justify-content:center; gap:10px; }
.btn-editar, .btn-eliminar { background:none; border:none; font-size:18px; cursor:pointer; }
.btn-editar { color:#4a2da0; } .btn-eliminar { color:#c62828; }
.btn-editar:hover { color:#6c63ff; } .btn-eliminar:hover { color:#ff1744; }

.modal-overlay { position:fixed; inset:0; background:rgba(0,0,0,0.5); display:none; justify-content:center; align-items:center; z-index:3000; }
.modal-overlay.active { display:flex; }
.modal-content { background:white; padding:25px; width:550px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.2); animation:pop .3s ease; }
@keyframes pop { 0%{transform:scale(.8);opacity:0;} 100%{transform:scale(1);opacity:1;} }
.modal-buttons { display:flex; justify-content:flex-end; gap:15px; margin-top:20px; }
.btn-cancelar { padding:10px 15px; background:#aaa; border-radius:8px; color:#fff; border:none; cursor:pointer; }
.btn-guardar { padding:10px 15px; background:#4a2da0; border-radius:8px; color:#fff; border:none; cursor:pointer; }
.btn-guardar:hover { background:#3a2180; }
.tab-content { display:none; }
.tab-content.active { display:block; }

/* === FORMULARIO MODAL MEJORADO === */
.formulario-horario {
    display: flex;
    flex-direction: column;
    gap: 15px;
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px 20px;
}

.campo {
    display: flex;
    flex-direction: column;
    gap: 5px;
}

.campo label {
    font-weight: 600;
    color: #4a2da0;
    font-size: 0.95rem;
}

.campo input,
.campo select {
    padding: 8px 10px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 0.95rem;
    outline: none;
    transition: border-color 0.2s;
}

.campo input:focus,
.campo select:focus {
    border-color: #4a2da0;
    box-shadow: 0 0 4px rgba(74,45,160,0.2);
}
</style>

<div class="horario-wrapper">

    <div class="horario-header-card">
        <h1>Gestión de Horarios</h1>
        <div class="horario-header-actions">
            <a href="#" class="btn-volver">Volver</a>
            <a href="#" class="btn-exportar">Exportar</a>
            <button onclick="abrirModal()" class="btn-agregar">+ Nuevo Horario</button>
        </div>
    </div>

    <div class="horario-tabs">
        <button class="tab-btn active" data-tab="mañana">Mañana</button>
        <button class="tab-btn" data-tab="tarde">Tarde</button>
        <button class="tab-btn" data-tab="fullday">Full Day</button>
    </div>

    <!-- === TURNOS === -->
    @foreach(['Mañana','Tarde','Full Day'] as $turnoNombre)
    <div class="tab-content {{ $loop->first ? 'active' : '' }}" id="{{ strtolower(str_replace(' ', '', $turnoNombre)) }}">
        <div class="tabla-card">
            <h2>Turno: {{ $turnoNombre }}</h2>
            <table class="tabla-moderna">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Curso</th>
                        <th>Docente</th>
                        <th>Día</th>
                        <th>Hora</th>
                        <th>Aula</th>
                        <th>Estado</th>
                        <th style="text-align:center;">Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @php $i=1; @endphp
                    @foreach($horarios->where('turno.nombre_turno', $turnoNombre) as $horario)
                    <tr>
                        <td>{{ $i++ }}</td>
                        <td>{{ $horario->curso->nombre_curso ?? 'Sin curso' }}</td>
                        <td>{{ $horario->docente->usuario->nombres ?? 'Sin docente' }}</td>
                        <td>{{ $horario->dia }}</td>
                        <td>{{ $horario->hora_inicio }} - {{ $horario->hora_fin }}</td>
                        <td>{{ $horario->aula }}</td>
                        <td><span class="estado {{ $horario->estado }}">{{ $horario->estado }}</span></td>
                        <td class="acciones">
                            <button class="btn-editar"><i class="fas fa-edit"></i></button>
                            <form action="{{ route('admin.horarios.eliminar', $horario->id_horario) }}" method="POST" onsubmit="return confirm('¿Eliminar horario?')">
                                @csrf @method('DELETE')
                                <button class="btn-eliminar"><i class="fas fa-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                    @if($i==1)
                    <tr><td colspan="8" style="text-align:center;">Sin horarios registrados</td></tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
    @endforeach
</div>

<!-- === MODAL NUEVO HORARIO === -->
<div id="modalAgregar" class="modal-overlay">
    <div class="modal-content">
        <h2 style="color:#4a2da0; text-align:center; margin-bottom:15px;">Registrar Nuevo Horario</h2>

        <form action="{{ route('admin.horarios.agregar') }}" method="POST" class="formulario-horario">
            @csrf

            <div class="form-grid">
                <div class="campo">
                    <label for="id_curso">Curso:</label>
                    <select name="id_curso" id="id_curso" required>
                        <option value="">Seleccione curso</option>
                        @foreach($cursos as $curso)
                            <option value="{{ $curso->id_curso }}">{{ $curso->nombre_curso }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="campo">
                    <label for="id_docente">Docente:</label>
                    <select name="id_docente" id="id_docente" required>
                        <option value="">Seleccione docente</option>
                        @foreach($docentes as $docente)
                            <option value="{{ $docente->id_docente }}">{{ $docente->usuario->nombres }} {{ $docente->usuario->apellidos }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="campo">
                    <label for="id_turno">Turno:</label>
                    <select name="id_turno" id="id_turno" required>
                        <option value="">Seleccione turno</option>
                        @foreach($turnos as $turno)
                            <option value="{{ $turno->id_turno }}">{{ $turno->nombre_turno }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="campo">
                    <label for="dia">Día:</label>
                    <select name="dia" id="dia" required>
                        <option value="">Seleccione día</option>
                        <option value="Lunes">Lunes</option>
                        <option value="Martes">Martes</option>
                        <option value="Miércoles">Miércoles</option>
                        <option value="Jueves">Jueves</option>
                        <option value="Viernes">Viernes</option>
                        <option value="Sábado">Sábado</option>
                    </select>
                </div>

                <div class="campo">
                    <label for="hora_inicio">Hora inicio:</label>
                    <input type="time" id="hora_inicio" name="hora_inicio" required>
                </div>

                <div class="campo">
                    <label for="hora_fin">Hora fin:</label>
                    <input type="time" id="hora_fin" name="hora_fin" required>
                </div>

                <div class="campo">
                    <label for="aula">Aula:</label>
                    <input type="text" id="aula" name="aula" placeholder="Ej. A101" required>
                </div>

                <div class="campo">
                    <label for="estado">Estado:</label>
                    <select name="estado" id="estado" required>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>
            </div>

            <div class="modal-buttons">
                <button type="button" class="btn-cancelar" onclick="cerrarModal()">Cancelar</button>
                <button type="submit" class="btn-guardar">Guardar</button>
            </div>
        </form>
    </div>
</div>


<script>
// Tabs
const tabs=document.querySelectorAll('.tab-btn');
const tabContents=document.querySelectorAll('.tab-content');
tabs.forEach(tab=>{
    tab.addEventListener('click',()=>{
        const target=tab.dataset.tab;
        tabs.forEach(t=>t.classList.remove('active'));
        tab.classList.add('active');
        tabContents.forEach(tc=>{
            tc.id===target ? tc.classList.add('active') : tc.classList.remove('active');
        });
    });
});

// Modal
function abrirModal(){ document.getElementById('modalAgregar').classList.add('active'); }
function cerrarModal(){ document.getElementById('modalAgregar').classList.remove('active'); }
</script>

@endsection
