@extends('app')

@section('content')
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Horario - Docente | Academia Medallón</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

  <div class="min-h-screen p-6">
    <h1 class="text-2xl font-bold mb-6">🗓️ Mi Horario de Clases</h1>

    <!-- INFORMACIÓN DEL DOCENTE -->
    <div class="bg-white rounded-2xl shadow p-5 mb-6">
      <p><strong>Docente:</strong> Prof. Juan Pérez Ramírez</p>
      <p><strong>Periodo:</strong> Octubre 2025</p>
    </div>

    <!-- TABLA DE HORARIO -->
    <div class="bg-white rounded-2xl shadow p-6 overflow-x-auto">
      <table class="min-w-full border-collapse text-sm">
        <thead>
          <tr class="bg-gray-50 text-gray-600 uppercase">
            <th class="py-3 px-4 border-b text-left">Hora</th>
            <th class="py-3 px-4 border-b text-center">Lunes</th>
            <th class="py-3 px-4 border-b text-center">Martes</th>
            <th class="py-3 px-4 border-b text-center">Miércoles</th>
            <th class="py-3 px-4 border-b text-center">Jueves</th>
            <th class="py-3 px-4 border-b text-center">Viernes</th>
            <th class="py-3 px-4 border-b text-center">Sábado</th>
          </tr>
        </thead>
        <tbody>
          <!-- FILA 1 -->
          <tr class="hover:bg-gray-50">
            <td class="py-3 px-4 border-b font-medium">08:00 - 09:30</td>
            <td class="py-3 px-4 border-b text-center bg-indigo-100 rounded-lg">
              Matemática<br><span class="text-xs text-gray-600">Sección A</span>
            </td>
            <td class="py-3 px-4 border-b text-center bg-green-100">
              Física<br><span class="text-xs text-gray-600">Sección B</span>
            </td>
            <td class="py-3 px-4 border-b text-center">—</td>
            <td class="py-3 px-4 border-b text-center bg-yellow-100">
              Matemática<br><span class="text-xs text-gray-600">Sección A</span>
            </td>
            <td class="py-3 px-4 border-b text-center">—</td>
            <td class="py-3 px-4 border-b text-center bg-blue-100">
              Simulacro<br><span class="text-xs text-gray-600">General</span>
            </td>
          </tr>

          <!-- FILA 2 -->
          <tr class="hover:bg-gray-50">
            <td class="py-3 px-4 border-b font-medium">10:00 - 11:30</td>
            <td class="py-3 px-4 border-b text-center">—</td>
            <td class="py-3 px-4 border-b text-center bg-yellow-100">
              Química<br><span class="text-xs text-gray-600">Sección C</span>
            </td>
            <td class="py-3 px-4 border-b text-center bg-indigo-100">
              Matemática<br><span class="text-xs text-gray-600">Sección B</span>
            </td>
            <td class="py-3 px-4 border-b text-center">—</td>
            <td class="py-3 px-4 border-b text-center bg-green-100">
              Física<br><span class="text-xs text-gray-600">Sección A</span>
            </td>
            <td class="py-3 px-4 border-b text-center">—</td>
          </tr>

          <!-- FILA 3 -->
          <tr class="hover:bg-gray-50">
            <td class="py-3 px-4 border-b font-medium">12:00 - 13:30</td>
            <td class="py-3 px-4 border-b text-center bg-red-100">
              Química<br><span class="text-xs text-gray-600">Sección C</span>
            </td>
            <td class="py-3 px-4 border-b text-center">—</td>
            <td class="py-3 px-4 border-b text-center bg-green-100">
              Física<br><span class="text-xs text-gray-600">Sección B</span>
            </td>
            <td class="py-3 px-4 border-b text-center bg-yellow-100">
              Matemática<br><span class="text-xs text-gray-600">Sección A</span>
            </td>
            <td class="py-3 px-4 border-b text-center">—</td>
            <td class="py-3 px-4 border-b text-center bg-gray-100">—</td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- LEYENDA -->
    <div class="mt-8 bg-white rounded-2xl shadow p-4">
      <h3 class="text-lg font-semibold mb-2">🔎 Leyenda de colores</h3>
      <div class="flex flex-wrap gap-4 text-sm">
        <div class="flex items-center gap-2"><span class="w-4 h-4 bg-indigo-100 border"></span> Matemática</div>
        <div class="flex items-center gap-2"><span class="w-4 h-4 bg-green-100 border"></span> Física</div>
        <div class="flex items-center gap-2"><span class="w-4 h-4 bg-yellow-100 border"></span> Química</div>
        <div class="flex items-center gap-2"><span class="w-4 h-4 bg-blue-100 border"></span> Simulacro</div>
      </div>
    </div>
  </div>

</body>
</html>
@endsection