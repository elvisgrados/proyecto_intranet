@extends('app')

@section('content')
<!doctype html>
<html lang="es">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Comunicaciones - Docente | Academia Medallón</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

  <div class="min-h-screen p-6">
    <h1 class="text-2xl font-bold mb-6">💬 Panel de Comunicaciones</h1>

    <!-- NUEVO MENSAJE -->
    <div class="bg-white rounded-2xl shadow p-6 mb-8">
      <h2 class="text-xl font-semibold mb-4">Enviar nuevo mensaje</h2>
      <form class="space-y-4">
        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Destinatario</label>
          <select class="border rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-indigo-500">
            <option value="">Seleccionar grupo o alumno</option>
            <option>Todos los alumnos</option>
            <option>Matemática - Sección A</option>
            <option>Física - Sección B</option>
            <option>Química - Sección C</option>
          </select>
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Asunto</label>
          <input type="text" placeholder="Ej. Recordatorio de evaluación" class="border rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-indigo-500">
        </div>

        <div>
          <label class="block text-sm font-medium text-gray-600 mb-1">Mensaje</label>
          <textarea rows="4" placeholder="Escribe el mensaje aquí..." class="border rounded-lg px-3 py-2 w-full focus:ring-2 focus:ring-indigo-500"></textarea>
        </div>

        <button type="button" class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">📤 Enviar mensaje</button>
      </form>
    </div>

    <!-- MENSAJES ENVIADOS / RECIBIDOS -->
    <div class="bg-white rounded-2xl shadow p-6">
      <h2 class="text-xl font-semibold mb-4">📨 Historial de Comunicaciones</h2>

      <div class="space-y-4">

        <!-- Mensaje 1 -->
        <div class="border rounded-xl p-4 hover:bg-gray-50">
          <div class="flex justify-between items-start">
            <div>
              <h3 class="font-semibold text-indigo-700">Recordatorio: Evaluación de Física</h3>
              <p class="text-sm text-gray-500">Enviado a: Física - Sección B · <span class="italic">08/10/2025</span></p>
              <p class="mt-2 text-gray-700">Estimados alumnos, les recuerdo que mañana realizaremos la evaluación N°2 sobre movimiento rectilíneo. Revisen sus apuntes.</p>
            </div>
            <button class="text-gray-400 hover:text-red-500">🗑️</button>
          </div>
        </div>

        <!-- Mensaje 2 -->
        <div class="border rounded-xl p-4 hover:bg-gray-50">
          <div class="flex justify-between items-start">
            <div>
              <h3 class="font-semibold text-indigo-700">Aviso: Cambio de horario en Química</h3>
              <p class="text-sm text-gray-500">Enviado a: Química - Sección C · <span class="italic">07/10/2025</span></p>
              <p class="mt-2 text-gray-700">La clase de mañana se moverá a las 10:30 a.m. debido a un ajuste de aula. Gracias por su comprensión.</p>
            </div>
            <button class="text-gray-400 hover:text-red-500">🗑️</button>
          </div>
        </div>

        <!-- Mensaje 3 -->
        <div class="border rounded-xl p-4 hover:bg-gray-50">
          <div class="flex justify-between items-start">
            <div>
              <h3 class="font-semibold text-indigo-700">General: Resultados del simulacro</h3>
              <p class="text-sm text-gray-500">Enviado a: Todos los alumnos · <span class="italic">05/10/2025</span></p>
              <p class="mt-2 text-gray-700">Los resultados del simulacro ya están disponibles en el panel de cada alumno. ¡Buen trabajo!</p>
            </div>
            <button class="text-gray-400 hover:text-red-500">🗑️</button>
          </div>
        </div>

      </div>
    </div>
  </div>

</body>
</html>
@endsection