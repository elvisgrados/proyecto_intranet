@extends('app')

@section('content')

<!-- ===== ESTILOS INLINE ===== -->
<style>
/* ===== HEADER ===== */
.admin-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    background: #f8f9fa;
    padding: 15px 25px;
    border-radius: 8px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin-bottom: 20px;
}
.header-left h1 { 
    font-size: 1.6rem; 
    font-weight: 700; 
    color: #1f2937; 
}
.header-right { 
    display: flex; 
    align-items: center; 
    gap: 15px; 
}
.user-info { 
    text-align: right; 
    line-height: 1.2; 
}
.user-name { 
    font-weight: 600; 
    color: #111827; 
}
.profile-link { 
    display: flex; 
    align-items: center; 
    gap: 5px; 
    background: #5b3cc4; 
    color: #fff;
    text-decoration: none; 
    padding: 8px 12px; 
    border-radius: 6px; 
    font-size: 0.9rem;
    transition: background 0.3s ease;
}
.profile-link:hover { 
    background: #4a2da0; 
}

/* ===== TARJETAS DE ALERTAS ===== */
.alerts-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px,1fr));
    gap: 15px;
    margin-bottom: 30px;
}
.alert-card {
    background: linear-gradient(145deg, #ffffff, #f1f1f1);
    padding: 20px;
    border-left: 6px solid;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    gap: 10px;
    transition: all 0.3s ease;
}
.alert-card:hover { 
    transform: translateY(-5px); 
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.alert-card h3 { 
    margin: 0; 
    font-size: 1.1rem; 
    color: #111827; 
    display: flex; 
    align-items: center; 
    gap: 10px; 
}
.alert-card p { 
    font-size: 0.95rem; 
    color: #4b5563; 
    line-height: 1.4;
}
/* Colores por tipo de alerta */
.alert-pago { border-color: #f59e0b; background: #fff7e6; }       
.alert-matricula { border-color: #3b82f6; background: #e6f0ff; }  
.alert-mantenimiento { border-color: #ef4444; background: #ffe6e6; } 

/* ===== LISTADO DETALLADO DE ALERTAS ===== */
.alert-list {
    margin-top: 20px;
    background: white;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    padding: 15px;
}
.alert-list h3 { 
    color: #4a2da0; 
    margin-bottom: 10px; 
}
.alert-list table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0 8px;
}
.alert-list th, .alert-list td {
    text-align: left;
    padding: 12px 15px;
    font-size: 0.95rem;
}
.alert-list tbody tr {
    background: #ffffff;
    box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    border-radius: 10px;
}
.alert-list th {
    background: #f3f4f6;
    color: #111827;
}

/* ===== MODAL ===== */
.modal { 
    display:none; 
    position:fixed; 
    top:0; left:0; 
    width:100%; height:100%; 
    background:rgba(0,0,0,0.5); 
    justify-content:center; 
    align-items:center; 
}
.modal-content { 
    background:white; 
    padding:30px; 
    border-radius:12px; 
    width:450px; 
    max-width:90%; 
    position:relative; 
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
}
.close-modal { 
    position:absolute; 
    top:15px; 
    right:20px; 
    font-size:24px; 
    cursor:pointer; 
    color:#555;
}
.form-group { margin-bottom:15px; }
.form-group label { display:block; margin-bottom:6px; font-weight:bold; }
.form-group input, .form-group select { width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; }

/* RESPONSIVE */
@media(max-width:768px){
    .alerts-container { grid-template-columns: 1fr; gap: 10px; }
    .admin-header { flex-direction: column; align-items: flex-start; gap: 10px; }
}
</style>

<!-- ===== HEADER ===== -->
<header class="admin-header">
    <div class="header-left">
        <h1>Alertas</h1>
    </div>
    <div class="header-right">
        <div class="user-info">
            <span class="user-name">Administrador</span>
        </div>
        <button id="btnAgregar" class="profile-link">Agregar Alerta</button>
    </div>
</header>

<!-- ===== TARJETAS PRINCIPALES ===== -->
<div class="alerts-container" id="alertsContainer">
    <!-- Se llenará con JS -->
</div>

<!-- ===== LISTADO DETALLADO ===== -->
<div class="alert-list">
    <h3>Historial de alertas</h3>
    <table>
        <thead>
            <tr>
                <th>Tipo</th>
                <th>Mensaje</th>
                <th>Fecha</th>
                <th>Estado</th>
            </tr>
        </thead>
        <tbody id="alertTableBody">
            <!-- Se llenará con JS -->
        </tbody>
    </table>
</div>

<!-- ===== MODAL ===== -->
<div class="modal" id="alertModal">
    <div class="modal-content">
        <span class="close-modal" id="closeModal">&times;</span>
        <h2 id="modalTitle">Agregar Alerta</h2>
        <form id="alertForm">
            <div class="form-group">
                <label>Tipo de Alerta:</label>
                <select id="tipo" required>
                    <option value="pago">Pago</option>
                    <option value="matricula">Matrícula</option>
                    <option value="mantenimiento">Mantenimiento</option>
                </select>
            </div>
            <div class="form-group">
                <label>Mensaje:</label>
                <input type="text" id="mensaje" required>
            </div>
            <div class="form-group">
                <label>Fecha:</label>
                <input type="date" id="fecha" required>
            </div>
            <div class="form-group">
                <label>Estado:</label>
                <select id="estado" required>
                    <option value="Pendiente">Pendiente</option>
                    <option value="Programado">Programado</option>
                    <option value="Resuelto">Resuelto</option>
                </select>
            </div>
            <div class="form-group">
                <button type="submit" class="profile-link">Guardar</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== JS INLINE ===== -->
<script>
document.addEventListener('DOMContentLoaded', ()=>{

    const alertsData = [
        {tipo:'pago', icon:'⚠️', mensaje:'5 pagos vencidos esta semana', fecha:'16/10/2025', estado:'Pendiente'},
        {tipo:'matricula', icon:'📢', mensaje:'Nueva matrícula pendiente de revisión', fecha:'15/10/2025', estado:'Pendiente'},
        {tipo:'mantenimiento', icon:'🛠️', mensaje:'Mantenimiento programado mañana', fecha:'17/10/2025', estado:'Programado'}
    ];

    const container = document.getElementById('alertsContainer');
    const tableBody = document.getElementById('alertTableBody');
    const modal = document.getElementById('alertModal');

    function renderAlerts(){
        container.innerHTML = '';
        tableBody.innerHTML = '';
        alertsData.forEach(alert=>{
            // Tarjetas principales
            const card = document.createElement('div');
            card.classList.add('alert-card', `alert-${alert.tipo}`);
            card.innerHTML = `<h3>${alert.icon} ${alert.tipo.charAt(0).toUpperCase() + alert.tipo.slice(1)}</h3>
                              <p>${alert.mensaje}</p>`;
            container.appendChild(card);

            // Tabla detallada
            const row = document.createElement('tr');
            row.innerHTML = `<td>${alert.tipo.charAt(0).toUpperCase() + alert.tipo.slice(1)}</td>
                             <td>${alert.mensaje}</td>
                             <td>${alert.fecha}</td>
                             <td>${alert.estado}</td>`;
            tableBody.appendChild(row);
        });
    }
    renderAlerts();

    // Modal
    document.getElementById('btnAgregar').addEventListener('click', ()=>{
        document.getElementById('modalTitle').textContent='Agregar Alerta';
        modal.style.display='flex';
    });
    document.getElementById('closeModal').addEventListener('click', ()=>{
        modal.style.display='none';
    });

    // Guardar alerta
    document.getElementById('alertForm').addEventListener('submit', e=>{
        e.preventDefault();
        const tipo = document.getElementById('tipo').value;
        const mensaje = document.getElementById('mensaje').value;
        const fecha = document.getElementById('fecha').value;
        const estado = document.getElementById('estado').value;
        const iconMap = { pago:'⚠️', matricula:'📢', mantenimiento:'🛠️' };
        alertsData.push({tipo, icon:iconMap[tipo], mensaje, fecha, estado});
        renderAlerts();
        modal.style.display='none';
        e.target.reset();
    });

});
</script>

@endsection
