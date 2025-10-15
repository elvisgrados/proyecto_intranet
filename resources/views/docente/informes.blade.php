@extends('app')

@section('content')
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Informes - Docente | Academia Medallón</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

  <div class="min-h-screen p-6">
    <h1 class="text-2xl font-bold mb-6">📊 Informes Académicos</h1>

    <!-- FILTROS -->
    <div class="bg-white rounded-2xl shadow p-5 mb-6 flex flex-col md:flex-row md:items-end md:justify-between gap-4">
      <div class="flex flex-col md:flex-row md:items-center gap-4">
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Curso</label>
          <select class="border rounded-lg px-3 py-2 w-56 focus:ring-2 focus:ring-indigo-500">
            <option>Matemática - Sección A</option>
            <option>Física - Sección B</option>
            <option>Química - Sección C</option>
          </select>
        </div>
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Periodo</label>
          <select class="border rounded-lg px-3 py-2 w-56 focus:ring-2 focus:ring-indigo-500">
            <option>Bimestre 1</option>
            <option>Bimestre 2</option>
            <option>Bimestre 3</option>
            <option>Bimestre 4</option>
          </select>
        </div>
      </div>
      <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">🔍 Generar informe</button>
    </div>

    <!-- RESUMEN GENERAL -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-8">
      <div class="bg-white rounded-2xl shadow p-4 text-center">
        <p class="text-sm text-gray-500">Promedio general</p>
        <p class="text-3xl font-bold text-indigo-600 mt-1">15.4</p>
      </div>
      <div class="bg-white rounded-2xl shadow p-4 text-center">
        <p class="text-sm text-gray-500">Asistencia promedio</p>
        <p class="text-3xl font-bold text-green-600 mt-1">92%</p>
      </div>
      <div class="bg-white rounded-2xl shadow p-4 text-center">
        <p class="text-sm text-gray-500">Evaluaciones realizadas</p>
        <p class="text-3xl font-bold mt-1">8</p>
      </div>
      <div class="bg-white rounded-2xl shadow p-4 text-center">
        <p class="text-sm text-gray-500">Alumnos en riesgo</p>
        <p class="text-3xl font-bold text-red-600 mt-1">3</p>
      </div>
    </div>

    <!-- TABLA DE RENDIMIENTO -->
    <div class="bg-white rounded-2xl shadow p-6">
      <h2 class="text-xl font-semibold mb-4">Rendimiento por alumno</h2>

      <table class="w-full border-collapse">
        <thead>
          <tr class="bg-gray-50 text-left text-gray-600 text-sm uppercase">
            <th class="py-3 px-4 border-b">#</th>
            <th class="py-3 px-4 border-b">Alumno</th>
            <th class="py-3 px-4 border-b text-center">Promedio</th>
            <th class="py-3 px-4 border-b text-center">Asistencia</th>
            <th class="py-3 px-4 border-b text-center">Estado</th>
            <th class="py-3 px-4 border-b text-center">Acciones</th>
          </tr>
        </thead>
        <tbody class="text-sm">
          <tr class="hover:bg-gray-50">
            <td class="py-3 px-4 border-b">1</td>
            <td class="py-3 px-4 border-b">Ana Rodríguez Pérez</td>
            <td class="py-3 px-4 border-b text-center">17.8</td>
            <td class="py-3 px-4 border-b text-center">98%</td>
            <td class="py-3 px-4 border-b text-center">
              <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Destacada</span>
            </td>
            <td class="py-3 px-4 border-b text-center">
              <button class="px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">Ver Detalle</button>
            </td>
          </tr>

          <tr class="hover:bg-gray-50">
            <td class="py-3 px-4 border-b">2</td>
            <td class="py-3 px-4 border-b">Luis Torres Medina</td>
            <td class="py-3 px-4 border-b text-center">12.4</td>
            <td class="py-3 px-4 border-b text-center">87%</td>
            <td class="py-3 px-4 border-b text-center">
              <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">Regular</span>
            </td>
            <td class="py-3 px-4 border-b text-center">
              <button class="px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">Ver Detalle</button>
            </td>
          </tr>

          <tr class="hover:bg-gray-50">
            <td class="py-3 px-4 border-b">3</td>
            <td class="py-3 px-4 border-b">Carla Mendoza Salazar</td>
            <td class="py-3 px-4 border-b text-center">9.8</td>
            <td class="py-3 px-4 border-b text-center">75%</td>
            <td class="py-3 px-4 border-b text-center">
              <span class="px-2 py-1 bg-red-100 text-red-700 rounded-full text-xs">En riesgo</span>
            </td>
            <td class="py-3 px-4 border-b text-center">
              <button class="px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">Ver Detalle</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- ALERTAS -->
    <div class="bg-yellow-50 border-l-4 border-yellow-400 text-yellow-800 p-4 rounded-xl mt-8">
      ⚠️ <strong>Nota:</strong> Los alumnos con estado "En riesgo" deben ser monitoreados y recibir apoyo académico adicional.
    </div>
  </div>

</body>
</html>
@endsection