@extends('app')

@section('content')
<style>
.dashboard-container { display:flex; flex-direction:column; gap:30px; padding:30px; }
.dashboard-header h1 { font-size:28px; font-weight:700; color:#4a2da0; }

.cards-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(230px,1fr)); gap:20px; }
.card-box { padding:22px; border-radius:16px; color:white; display:flex; flex-direction:column; gap:10px; position:relative; transition:.3s ease; overflow:hidden; }
.card-box:hover { transform:translateY(-5px); box-shadow:0 8px 18px rgba(0,0,0,0.2); }
.card-box i { font-size:32px; position:absolute; right:18px; top:18px; opacity:.8; }
.card-box h3 { margin:0; font-size:16px; }
.card-box .value { font-size:30px; font-weight:800; }

.card-1 { background:linear-gradient(135deg,#6c63ff,#8a82ff); }
.card-2 { background:linear-gradient(135deg,#ff6b6b,#ff8a8a); }
.card-3 { background:linear-gradient(135deg,#20c997,#4be6b0); }
.card-4 { background:linear-gradient(135deg,#ffb347,#ffcc67); }
.card-5 { background:linear-gradient(135deg,#17a2b8,#48d1e2); }

.graph-container { background:#fff; padding:20px; border-radius:14px; box-shadow:0 2px 8px rgba(0,0,0,0.08); margin-top:20px; }
.graph-container h3 { margin-bottom:15px; }

.table-modern { width:100%; border-collapse:separate; border-spacing:0 10px; margin-top:15px; }
.table-modern thead th { background:#6c63ff; color:#fff; padding:12px 18px; font-weight:600; text-align:left; border-radius:12px; }
.table-modern tbody tr { background:#fff; box-shadow:0 2px 8px rgba(0,0,0,0.08); border-radius:12px; }
.table-modern tbody td { padding:14px 18px; color:#111827; font-size:.95rem; }
.table-modern tbody tr td:first-child { border-top-left-radius:12px; border-bottom-left-radius:12px; }
.table-modern tbody tr td:last-child { border-top-right-radius:12px; border-bottom-right-radius:12px; }

.columns { display:grid; grid-template-columns:1fr 1fr; gap:20px; margin-top:20px; }
.box { background:#fff; padding:20px; border-radius:14px; box-shadow:0 2px 8px rgba(0,0,0,0.08); }
.box h3 { margin-bottom:12px; }
.list-item { padding:10px 5px; border-bottom:1px solid #eee; }
.list-item:last-child { border-bottom:none; }

@media (max-width:768px){
    .dashboard-container { padding:15px; }
    .columns { grid-template-columns:1fr; }
    .card-box i { font-size:26px; top:15px; }
    .card-box .value { font-size:24px; }
}
</style>

<div class="dashboard-container">
    <div class="dashboard-header">
        <h1>Panel Administrativo</h1>
    </div>

    <!-- TARJETAS -->
    <div class="cards-grid">
        <div class="card-box card-1">
            <i class="fas fa-user-graduate"></i>
            <h3>Total Alumnos</h3>
            <div class="value">{{ $totalAlumnos }}</div>
        </div>
        <div class="card-box card-2">
            <i class="fas fa-chalkboard-teacher"></i>
            <h3>Total Docentes</h3>
            <div class="value">{{ $totalDocentes }}</div>
        </div>
        <div class="card-box card-3">
            <i class="fas fa-layer-group"></i>
            <h3>Total Carreras</h3>
            <div class="value">{{ $totalCarreras }}</div>
        </div>
        <div class="card-box card-4">
            <i class="fas fa-coins"></i>
            <h3>Ingresos</h3>
            <div class="value">S/ {{ number_format($totalIngresos,2,',','.') }}</div>
        </div>
        <div class="card-box card-5">
            <i class="fas fa-file-invoice-dollar"></i>
            <h3>Egresos</h3>
            <div class="value">S/ {{ number_format($totalEgresos,2,',','.') }}</div>
        </div>
    </div>

    <!-- GRAFICOS -->
    <div class="graph-container">
        <h3>Ingresos vs Egresos por Mes</h3>
        <canvas id="graficoPagos" height="200"></canvas>
    </div>

    <div class="graph-container">
        <h3>Matriculas por Carrera</h3>
        <canvas id="graficoCarreras" height="200"></canvas>
    </div>

    <!-- TABLA ULTIMAS MATRICULAS -->
    <div class="table-box">
        <h3>Últimas Matriculas</h3>
        <table class="table-modern">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Alumno</th>
                    <th>Carrera</th>
                    <th>Turno</th>
                    <th>Fecha</th>
                </tr>
            </thead>
            <tbody>
                @forelse($ultimasMatriculas as $index => $mat)
                <tr>
                    <td>{{ $index+1 }}</td>
                    <td>{{ $mat->alumno->usuario->nombres ?? '' }} {{ $mat->alumno->usuario->apellidos ?? '' }}</td>
                    <td>{{ $mat->carrera->nombre_carrera ?? '' }}</td>
                    <td>{{ $mat->turno->nombre_turno ?? '' }}</td>
                    <td>{{ \Carbon\Carbon::parse($mat->created_at)->format('d/m/Y') }}</td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" style="text-align:center;">Sin datos</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- ALERTAS Y REPORTES -->
    <div class="columns">
        <div class="box">
            <h3>Últimas Alertas</h3>
            @forelse($alertas as $alert)
                <div class="list-item">{{ $alert->mensaje }}</div>
            @empty
                <div class="list-item">Sin datos</div>
            @endforelse
        </div>

        <div class="box">
            <h3>Últimos Reportes</h3>
            @forelse($reportes as $rep)
                <div class="list-item">{{ $rep->titulo }}</div>
            @empty
                <div class="list-item">Sin datos</div>
            @endforelse
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Función para generar colores aleatorios
function generarColores(n){
    const colores = [];
    for(let i=0; i<n; i++){
        const r = Math.floor(Math.random()*255);
        const g = Math.floor(Math.random()*255);
        const b = Math.floor(Math.random()*255);
        colores.push(`rgba(${r},${g},${b},0.7)`);
    }
    return colores;
}

// Gráfico de barras: ingresos vs egresos
const ctxPagos = document.getElementById('graficoPagos').getContext('2d');
new Chart(ctxPagos, {
    type: 'bar',
    data: {
        labels: @json(array_values($meses)),
        datasets: [
            {
                label: 'Ingresos',
                data: @json($ingresos),
                backgroundColor: '#6c63ff'
            },
            {
                label: 'Egresos',
                data: @json($egresos),
                backgroundColor: '#ff6b6b'
            }
        ]
    },
    options: {
        responsive:true,
        plugins:{
            legend:{position:'top'},
            tooltip:{mode:'index', intersect:false, callbacks:{
                label: function(context){
                    return `S/ ${context.raw.toLocaleString('es-PE', {minimumFractionDigits:2})}`;
                }
            }}
        },
        scales:{y:{beginAtZero:true}}
    }
});

// Gráfico de pie: matriculas por carrera con colores dinámicos
const ctxCarr = document.getElementById('graficoCarreras').getContext('2d');
const carrerasLabels = @json($matriculasPorCarrera->pluck('nombre_carrera'));
const carrerasData = @json($matriculasPorCarrera->pluck('matriculas_count'));
new Chart(ctxCarr, {
    type:'pie',
    data:{
        labels: carrerasLabels,
        datasets:[{
            label:'Matriculados',
            data: carrerasData,
            backgroundColor: generarColores(carrerasData.length),
            borderColor:'#fff',
            borderWidth:2
        }]
    },
    options:{
        responsive:true,
        plugins:{
            legend:{position:'bottom'},
            tooltip:{callbacks:{
                label: function(context){
                    return `${context.label}: ${context.raw} alumnos`;
                }
            }}
        }
    }
});
</script>
@endsection
