@extends('app')

@section('content')
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Asistencia - Docente | Academia Medallón</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

  <div class="min-h-screen p-6">
    <h1 class="text-2xl font-bold mb-6">📅 Control de Asistencia</h1>

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
          <label class="block text-sm font-medium text-gray-600 mb-1">Fecha</label>
          <input type="date" class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-indigo-500">
        </div>
      </div>

      <button class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">Buscar</button>
    </div>

    <!-- TABLA DE ASISTENCIA -->
    <div class="bg-white rounded-2xl shadow p-6">
      <h2 class="text-xl font-semibold mb-4">Lista de Alumnos - Matemática (12/10/2025)</h2>

      <table class="w-full border-collapse">
        <thead>
          <tr class="bg-gray-50 text-left text-gray-600 text-sm uppercase">
            <th class="py-3 px-4 border-b">#</th>
            <th class="py-3 px-4 border-b">Nombre del alumno</th>
            <th class="py-3 px-4 border-b text-center">Estado</th>
            <th class="py-3 px-4 border-b text-center">Observación</th>
          </tr>
        </thead>
        <tbody class="text-sm">
          <tr class="hover:bg-gray-50">
            <td class="py-3 px-4 border-b">1</td>
            <td class="py-3 px-4 border-b">Ana Rodríguez Pérez</td>
            <td class="py-3 px-4 border-b text-center">
              <select class="border rounded px-2 py-1">
                <option>Presente</option>
                <option>Ausente</option>
                <option>Tarde</option>
              </select>
            </td>
            <td class="py-3 px-4 border-b text-center">
              <input type="text" placeholder="Ej. llegó tarde" class="border rounded px-2 py-1 w-48">
            </td>
          </tr>

          <tr class="hover:bg-gray-50">
            <td class="py-3 px-4 border-b">2</td>
            <td class="py-3 px-4 border-b">Luis Torres Medina</td>
            <td class="py-3 px-4 border-b text-center">
              <select class="border rounded px-2 py-1">
                <option>Presente</option>
                <option selected>Ausente</option>
                <option>Tarde</option>
              </select>
            </td>
            <td class="py-3 px-4 border-b text-center">
              <input type="text" placeholder="-" class="border rounded px-2 py-1 w-48">
            </td>
          </tr>

          <tr class="hover:bg-gray-50">
            <td class="py-3 px-4 border-b">3</td>
            <td class="py-3 px-4 border-b">Carla Mendoza Salazar</td>
            <td class="py-3 px-4 border-b text-center">
              <select class="border rounded px-2 py-1">
                <option selected>Presente</option>
                <option>Ausente</option>
                <option>Tarde</option>
              </select>
            </td>
            <td class="py-3 px-4 border-b text-center">
              <input type="text" placeholder="-" class="border rounded px-2 py-1 w-48">
            </td>
          </tr>
        </tbody>
      </table>

      <div class="mt-6 text-right">
        <button class="px-5 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">💾 Guardar asistencia</button>
      </div>
    </div>
  </div>

</body>
</html>
@endsection