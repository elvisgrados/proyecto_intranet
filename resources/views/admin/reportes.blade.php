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
.header-left h1 { font-size: 1.6rem; font-weight: 700; color: #1f2937; }
.header-right { display: flex; align-items: center; gap: 15px; }
.user-info { text-align: right; line-height: 1.2; }
.user-name { font-weight: 600; color: #111827; }
.profile-link { 
    display: flex; align-items: center; gap: 5px; background: #5b3cc4; color: #fff;
    text-decoration: none; padding: 8px 12px; border-radius: 6px; font-size: 0.9rem;
    transition: background 0.3s ease;
}
.profile-link:hover { background: #4a2da0; }

/* ===== BOTÓN AGREGAR ===== */
.btn-primary { 
    background:#5b3cc4; 
    color:white; 
    border:none; 
    padding:10px 15px; 
    border-radius:6px; 
    cursor:pointer; 
    transition:0.3s; 
    margin-bottom:15px; 
}
.btn-primary:hover { background:#4a2da0; }

/* ===== TARJETAS DE REPORTES ===== */
.cards-container {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px,1fr));
    gap: 15px;
    margin-bottom: 25px;
}
.card {
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    display: flex;
    flex-direction: column;
    gap: 10px;
    cursor: pointer;
    transition: transform 0.2s ease;
}
.card:hover { transform: translateY(-5px); }
.card h3 { margin: 0; font-size: 1rem; color: #4a2da0; display: flex; align-items: center; gap: 8px; }
.card p { font-size: 1.2rem; font-weight: 600; color: #1f2937; }

/* ===== SECCION DETALLE ===== */
.detail-section {
    margin-top: 20px;
    display: none;
    overflow-x: auto; 
}
.detail-section.active { display: block; }
.detail-section h3 { margin-bottom: 10px; color: #4a2da0; font-size: 1.2rem; }

/* ===== TABLAS ===== */
table { 
    width: 100%; 
    border-collapse: collapse; 
    background: white; 
    box-shadow: 0 2px 8px rgba(0,0,0,0.08); 
    margin-top: 15px; 
    table-layout: auto; 
}
th, td { padding: 12px; text-align: left; border-bottom: 1px solid #eee; word-wrap: break-word; }

/* ===== GRAFICOS ===== */
.detail-section canvas { 
    background: white; 
    padding: 15px; 
    border-radius: 10px; 
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    display: block;
    margin: 0 auto;
    width: 100% !important;   
    height: auto !important;  
}
canvas.doughnut-chart {
    max-width: 500px; 
    height: 400px !important;
    margin: 30px auto; 
}

/* ===== MODAL ===== */
.modal { display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,0.5); justify-content:center; align-items:center; }
.modal-content { background:white; padding:25px; border-radius:8px; width:400px; position:relative; }
.close-modal { position:absolute; top:10px; right:15px; font-size:24px; cursor:pointer; }
.form-group { margin-bottom:15px; }
.form-group label { display:block; margin-bottom:6px; font-weight:bold; }
.form-group input, .form-group select { width:100%; padding:8px; border:1px solid #ccc; border-radius:4px; }

/* ===== RESPONSIVE ===== */
@media(max-width:768px){
    .cards-container { grid-template-columns: 1fr; }
}

</style>
<!-- ===== HEADER ===== -->
<header class="admin-header">
    <div class="header-left">
        <h1>Reportes</h1>
    </div>
    <div class="header-right">
        <div class="user-info">
            <span class="user-name">Administrador</span>
        </div>
    </div>
</header>

<!-- ===== BOTÓN AGREGAR REPORTE ===== -->
<button class="btn-primary" id="btnAgregar">Agregar Reporte</button>

<!-- TARJETAS DE REPORTES -->
<div class="cards-container">
    <div class="card" data-report="pagos">
        <h3>💰 Reporte de Egresos e Ingresos</h3>
        <p id="totalPagosCard">0 S/</p>
    </div>
    <div class="card" data-report="matriculas">
        <h3>🎓 Reporte de Carreras</h3>
        <p id="totalMatriculasCard">0 alumnos</p>
    </div>
    <div class="card" data-report="asistencia">
        <h3>📈 Reporte de asistencia</h3>
        <p id="promAsistenciaCard">0%</p>
    </div>
    <div class="card" data-report="general">
        <h3>📊 Reporte general</h3>
        <p>Resumen completo</p>
    </div>
</div>

<!-- ===== DETALLES ===== -->
<div class="detail-section" id="reportDetail">
    <h3 id="detailTitle"></h3>
    <canvas id="reportChart" height="150"></canvas>
    <div id="reportTableContainer"></div>
</div>

<!-- ===== MODAL AGREGAR REPORTE ===== -->
<div class="modal" id="reportModal">
    <div class="modal-content">
        <span class="close-modal" id="closeModal">&times;</span>
        <h2 id="modalTitle">Agregar Reporte</h2>
        <form id="reportForm">
            <div class="form-group"><label>Nombre del Reporte:</label><input type="text" id="nombreReporte" required></div>
            <div class="form-group"><label>Tipo:</label>
                <select id="tipoReporte">
                    <option value="pagos">Pagos</option>
                    <option value="matriculas">Matrículas</option>
                    <option value="asistencia">Asistencia</option>
                    <option value="general">General</option>
                </select>
            </div>
            <div class="form-group"><button type="submit" class="btn-primary">Guardar</button></div>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', ()=>{

    // Datos simulados
    const pagosMes = [1200, 1500, 1100, 1700, 2000, 1800]; // ingresos
    const pagosDocentes = [500, 700, 600, 800, 900, 750];  // egresos
    const meses = ["Ene","Feb","Mar","Abr","May","Jun"];
    
    const matriculasCarrera = [
        {carrera:"Medicina", total:25},
        {carrera:"Derecho", total:30},
        {carrera:"Ingeniería", total:20},
        {carrera:"Arquitectura", total:15}
    ];
    
    const asistencia = [
        {curso:"Matemática", porcentaje:90},
        {curso:"Lenguaje", porcentaje:85},
        {curso:"Ciencias", porcentaje:95},
        {curso:"Historia", porcentaje:80}
    ];

    // Actualizar tarjetas resumen
    document.getElementById('totalPagosCard').textContent = (pagosMes.reduce((a,b)=>a+b,0) - pagosDocentes.reduce((a,b)=>a+b,0)) + ' S/';
    document.getElementById('totalMatriculasCard').textContent = matriculasCarrera.reduce((a,b)=>a+b.total,0) + ' matriculados';
    document.getElementById('promAsistenciaCard').textContent = (asistencia.reduce((a,b)=>a+b.porcentaje,0)/asistencia.length).toFixed(1)+'%';

    const cards = document.querySelectorAll('.card');
    const detailSection = document.getElementById('reportDetail');
    const detailTitle = document.getElementById('detailTitle');
    const reportTableContainer = document.getElementById('reportTableContainer');
    let chartInstance = null;

    cards.forEach(card=>{
        card.addEventListener('click', ()=>{
            const report = card.dataset.report;
            detailSection.classList.add('active');
            reportTableContainer.innerHTML = '';
            detailTitle.textContent = card.querySelector('h3').textContent;

            const canvas = document.getElementById('reportChart');
            canvas.classList.remove('doughnut-chart');

            if(chartInstance) chartInstance.destroy();

            if(report==='pagos'){
                canvas.style.display = 'block';
                const ctx = canvas.getContext('2d');
                chartInstance = new Chart(ctx, {
                    type: 'bar',
                    data: { 
                        labels: meses,
                        datasets:[
                            {label:'Ingresos', data: pagosMes, backgroundColor:'#4a2da0'},
                            {label:'Pagos a docentes', data: pagosDocentes, backgroundColor:'#c62828'}
                        ]
                    },
                    options: { responsive:true, plugins:{legend:{position:'top'}} }
                });

                // Mostrar tabla comparativa debajo
                const table = document.createElement('table');
                table.innerHTML = `<thead>
                    <tr><th>Mes</th><th>Ingresos S/</th><th>Pagos docentes S/</th></tr>
                </thead>
                <tbody>
                    ${meses.map((m,i)=>`<tr>
                        <td>${m}</td>
                        <td>${pagosMes[i]}</td>
                        <td>${pagosDocentes[i]}</td>
                    </tr>`).join('')}
                </tbody>`;
                reportTableContainer.appendChild(table);

            } else if(report==='matriculas'){
                canvas.style.display = 'block';
                canvas.classList.add('doughnut-chart');
                const ctx = canvas.getContext('2d');
                chartInstance = new Chart(ctx, {
                    type: 'doughnut',
                    data:{
                        labels: matriculasCarrera.map(m=>m.carrera),
                        datasets:[{data: matriculasCarrera.map(m=>m.total), backgroundColor:['#5b3cc4','#4a2da0','#a78bfa','#c084fc']}]
                    },
                    options:{ responsive:true, maintainAspectRatio:false }
                });

            } else if(report==='asistencia'){
                canvas.style.display = 'block';
                const ctx = canvas.getContext('2d');
                chartInstance = new Chart(ctx,{
                    type:'bar',
                    data:{
                        labels: asistencia.map(a=>a.curso),
                        datasets:[{label:'% Asistencia', data: asistencia.map(a=>a.porcentaje), backgroundColor:['#5b3cc4','#4a2da0','#a78bfa','#c084fc']}]
                    },
                    options:{ 
                        responsive:true, 
                        scales:{ y:{beginAtZero:true, max:100} }
                    }
                });

            } else if(report==='general'){
                canvas.style.display = 'none';
                const table = document.createElement('table');
                table.innerHTML = `<thead>
                    <tr><th>Curso/Carrera</th><th>Matriculados</th><th>% Asistencia</th><th>Ingresos S/</th><th>Pagos docentes S/</th></tr>
                </thead>
                <tbody>
                    ${matriculasCarrera.map((m,i)=>`<tr>
                        <td>${m.carrera}</td>
                        <td>${m.total}</td>
                        <td>${asistencia[i] ? asistencia[i].porcentaje : '-'}</td>
                        <td>${pagosMes[i] || 0}</td>
                        <td>${pagosDocentes[i] || 0}</td>
                    </tr>`).join('')}
                </tbody>`;
                reportTableContainer.appendChild(table);
            }
        });
    });

    // ===== MODAL AGREGAR REPORTE =====
    const modal = document.getElementById('reportModal');
    document.getElementById('btnAgregar').addEventListener('click', ()=>{
        document.getElementById('modalTitle').textContent='Agregar Reporte';
        document.getElementById('reportForm').reset();
        modal.style.display='flex';
    });
    document.getElementById('closeModal').addEventListener('click', ()=>{ modal.style.display='none'; });

    document.getElementById('reportForm').addEventListener('submit', (e)=>{
        e.preventDefault();
        const nombre = document.getElementById('nombreReporte').value;
        const tipo = document.getElementById('tipoReporte').value;
        alert(`Reporte "${nombre}" de tipo "${tipo}" agregado correctamente!`);
        modal.style.display='none';
        document.getElementById('reportForm').reset();
    });
});

</script>


@endsection
