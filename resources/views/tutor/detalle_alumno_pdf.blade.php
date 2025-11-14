<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Detalle del Alumno - {{ $alumno->usuario->nombres ?? '' }} {{ $alumno->usuario->apellidos ?? '' }}</title>
  <style>
    body {
      font-family: DejaVu Sans, sans-serif;
      margin: 25px;
      color: #222;
      font-size: 13px;
    }
    h1, h2 {
      color: #2c3e50;
    }
    h1 {
      text-align: center;
      font-size: 22px;
      margin-bottom: 5px;
    }
    h2 {
      background: #4f46e5;
      color: white;
      padding: 5px 10px;
      border-radius: 5px;
      font-size: 16px;
      margin-top: 25px;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }
    th, td {
      border: 1px solid #ddd;
      padding: 6px 8px;
      text-align: left;
    }
    th {
      background-color: #6366f1;
      color: white;
    }
    tr:nth-child(even) {
      background: #f4f6f9;
    }
    .info {
      margin-bottom: 15px;
    }
    .info strong {
      color: #111;
    }
    footer {
      text-align: center;
      font-size: 11px;
      margin-top: 30px;
      color: #555;
    }
  </style>
</head>
<body>

  <h1>Reporte del Alumno</h1>
  <p class="info">
    <strong>Nombre:</strong> {{ $alumno->usuario->nombres ?? '' }} {{ $alumno->usuario->apellidos ?? '' }} <br>
    <strong>DNI:</strong> {{ $alumno->usuario->dni ?? '---' }} <br>
    <strong>Fecha de generación:</strong> {{ now()->format('d/m/Y H:i') }}
  </p>

  <h2>🧩 Notas</h2>
  <table>
    <thead>
      <tr>
        <th>Evaluación</th>
        <th>Puntaje</th>
        <th>Fecha</th>
      </tr>
    </thead>
    <tbody>
      @forelse($notas as $n)
        <tr>
          <td>{{ $n->evaluacion }}</td>
          <td>{{ $n->puntaje }}</td>
          <td>{{ \Carbon\Carbon::parse($n->fecha_registro)->format('d/m/Y') }}</td>
        </tr>
      @empty
        <tr><td colspan="3" style="text-align:center;">Sin notas registradas</td></tr>
      @endforelse
    </tbody>
  </table>

  <h2>📅 Asistencias</h2>
  <table>
    <thead>
      <tr>
        <th>Fecha</th>
        <th>Estado</th>
        <th>Puntaje</th>
      </tr>
    </thead>
    <tbody>
      @forelse($asistencias as $as)
        <tr>
          <td>{{ \Carbon\Carbon::parse($as->fecha)->format('d/m/Y') }}</td>
          <td>{{ ucfirst($as->estado) }}</td>
          <td>{{ $as->puntaje }}</td>
        </tr>
      @empty
        <tr><td colspan="3" style="text-align:center;">Sin registros de asistencia</td></tr>
      @endforelse
    </tbody>
  </table>

  <footer>
    © {{ date('Y') }} Sistema Intranet — Reporte generado automáticamente.
  </footer>

</body>
</html>
    