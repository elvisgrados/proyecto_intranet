@extends('app')

@section('content')
<!-- Fondo gris -->
<div class="min-h-screen bg-gray-100 p-10">
  <!-- Tarjeta blanca principal -->
  <div class="bg-white rounded-2xl shadow-lg p-8">
    <!-- Encabezado -->
    <h2 class="text-2xl font-semibold text-gray-800 flex items-center gap-2">
      👋 Hola, {{ Auth::user()->nombres ?? 'Alumno' }}
    </h2>
    <p class="text-gray-600 mt-1">
      Bienvenido a tu portal académico. Desde aquí puedes revisar tu horario, cursos, pagos y tus resultados de exámenes.
    </p>

    <!-- Tarjetas internas -->
    <div class="flex flex-wrap justify-between gap-6 mt-8">
      <!-- Tarjeta 1 -->
      <div class="flex-1 min-w-[250px] bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition text-center py-6">
        <i class="fa-solid fa-calendar-days text-blue-500 text-4xl mb-3"></i>
        <h3 class="text-lg font-medium text-gray-800 mb-2">Horario</h3>
        <a href="{{ route('alumno.horario') }}"
           class="inline-block border border-blue-500 text-blue-500 hover:bg-blue-500 hover:text-white text-sm font-medium px-6 py-1.5 rounded-lg transition">
           Ver
        </a>
      </div>

      <!-- Tarjeta 2 -->
      <div class="flex-1 min-w-[250px] bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition text-center py-6">
        <i class="fa-solid fa-book text-green-500 text-4xl mb-3"></i>
        <h3 class="text-lg font-medium text-gray-800 mb-2">Cursos</h3>
        <a href="{{ route('alumno.cursos') }}"
           class="inline-block border border-green-500 text-green-500 hover:bg-green-500 hover:text-white text-sm font-medium px-6 py-1.5 rounded-lg transition">
           Ver
        </a>
      </div>

      <!-- Tarjeta 3 -->
      <div class="flex-1 min-w-[250px] bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition text-center py-6">
        <i class="fa-solid fa-chart-bar text-yellow-500 text-4xl mb-3"></i>
        <h3 class="text-lg font-medium text-gray-800 mb-2">Resultados</h3>
        <a href="{{ route('alumno.resultados') }}"
           class="inline-block border border-yellow-500 text-yellow-500 hover:bg-yellow-500 hover:text-white text-sm font-medium px-6 py-1.5 rounded-lg transition">
           Ver
        </a>
      </div>
    </div>
  </div>
</div>
@endsection