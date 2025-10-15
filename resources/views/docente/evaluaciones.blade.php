@extends('app')

@section('content')
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Evaluaciones - Docente | Academia Medallón</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

  <div class="min-h-screen p-6">
    <h1 class="text-2xl font-bold mb-6">📝 Evaluaciones</h1>

    <!-- Botón nueva evaluación -->
    <div class="mb-6">
      <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
        ➕ Crear nueva evaluación
      </button>
    </div>

    <!-- Resumen general -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
      <div class="bg-white rounded-2xl shadow p-4">
        <div class="text-sm text-gray-500">Evaluaciones totales</div>
        <div class="text-3xl font-bold mt-2">12</div>
      </div>
      <div class="bg-white rounded-2xl shadow p-4">
        <div class="text-sm text-gray-500">Pendientes de corrección</div>
        <div class="text-3xl font-bold mt-2 text-red-600">3</div>
      </div>
      <div class="bg-white rounded-2xl shadow p-4">
        <div class="text-sm text-gray-500">Calificadas</div>
        <div class="text-3xl font-bold mt-2 text-green-600">9</div>
      </div>
    </div>

    <!-- Tabla de evaluaciones -->
    <div class="bg-white rounded-2xl shadow p-6">
      <h2 class="text-xl font-semibold mb-4">Lista de Evaluaciones</h2>

      <table class="w-full border-collapse">
        <thead>
          <tr class="bg-gray-50 text-left text-gray-600 text-sm uppercase">
            <th class="py-3 px-4 border-b">Curso</th>
            <th class="py-3 px-4 border-b">Título</th>
            <th class="py-3 px-4 border-b">Fecha</th>
            <th class="py-3 px-4 border-b">Estado</th>
            <th class="py-3 px-4 border-b text-center">Acciones</th>
          </tr>
        </thead>
        <tbody class="text-sm">
          <tr class="hover:bg-gray-50">
            <td class="py-3 px-4 border-b">Matemática</td>
            <td class="py-3 px-4 border-b">Evaluación N°1 - Funciones</td>
            <td class="py-3 px-4 border-b">03/10/2025</td>
            <td class="py-3 px-4 border-b">
              <span class="px-2 py-1 bg-green-100 text-green-700 rounded-full text-xs">Calificada</span>
            </td>
            <td class="py-3 px-4 border-b text-center">
              <button class="px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">Ver</button>
              <button class="px-3 py-1 border rounded hover:bg-gray-100">Editar</button>
            </td>
          </tr>

          <tr class="hover:bg-gray-50">
            <td class="py-3 px-4 border-b">Física</td>
            <td class="py-3 px-4 border-b">Evaluación N°2 - Movimiento Rectilíneo</td>
            <td class="py-3 px-4 border-b">08/10/2025</td>
            <td class="py-3 px-4 border-b">
              <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">Pendiente</span>
            </td>
            <td class="py-3 px-4 border-b text-center">
              <button class="px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">Corregir</button>
              <button class="px-3 py-1 border rounded hover:bg-gray-100">Editar</button>
            </td>
          </tr>

          <tr class="hover:bg-gray-50">
            <td class="py-3 px-4 border-b">Química</td>
            <td class="py-3 px-4 border-b">Evaluación N°1 - Enlace Químico</td>
            <td class="py-3 px-4 border-b">10/10/2025</td>
            <td class="py-3 px-4 border-b">
              <span class="px-2 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs">Pendiente</span>
            </td>
            <td class="py-3 px-4 border-b text-center">
              <button class="px-3 py-1 bg-indigo-600 text-white rounded hover:bg-indigo-700">Corregir</button>
              <button class="px-3 py-1 border rounded hover:bg-gray-100">Editar</button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>
@endsection