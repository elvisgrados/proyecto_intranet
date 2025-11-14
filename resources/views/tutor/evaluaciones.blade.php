@extends('app')

@section('content')
<div x-data="{ openModal: false, showSuccess: false }" class="min-h-screen p-6 bg-gray-100 font-sans text-gray-800">
    <h1 class="text-2xl font-bold mb-6">Evaluaciones - Panel del Tutor</h1>

    {{-- ✅ BOTÓN PARA ABRIR MODAL --}}
    <div class="flex justify-end mb-6">
        <button @click="openModal = true" 
            class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition">
            ➕ Crear Nueva Evaluación
        </button>
    </div>

    {{-- ✅ MODAL CREAR NUEVA EVALUACIÓN --}}
    <div x-show="openModal" 
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         x-transition>
        <div @click.away="openModal = false"
             class="bg-white rounded-xl shadow-xl w-full max-w-lg p-6 relative">
            <h2 class="text-lg font-semibold mb-4">➕ Nueva Evaluación</h2>

            <form method="POST" action="{{ route('tutor.evaluaciones.nueva') }}" 
                  class="grid grid-cols-2 gap-4"
                  @submit.prevent="
                      const form = $el;
                      fetch(form.action, {
                          method: 'POST',
                          headers: {
                              'X-CSRF-TOKEN': '{{ csrf_token() }}',
                              'Content-Type': 'application/json'
                          },
                          body: JSON.stringify({
                              titulo: form.titulo.value,
                              tipo: form.tipo.value,
                              semana: form.semana.value,
                              fecha: form.fecha.value
                          })
                      })
                      .then(r => r.ok ? (openModal = false, showSuccess = true, form.reset()) : alert('Error al crear la evaluación'))
                  ">
                @csrf
                <div class="col-span-2">
                    <label class="block text-sm font-medium">Título</label>
                    <input type="text" name="titulo" required class="w-full border rounded px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium">Tipo</label>
                    <select name="tipo" required class="w-full border rounded px-3 py-2">
                        <option value="semanal">Semanal</option>
                        <option value="simulacro">Simulacro</option>
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium">Semana (opcional)</label>
                    <input type="number" name="semana" class="w-full border rounded px-3 py-2" min="1" max="20">
                </div>
                <div class="col-span-2">
                    <label class="block text-sm font-medium">Fecha</label>
                    <input type="date" name="fecha" class="w-full border rounded px-3 py-2">
                </div>
                <div class="col-span-2 text-right mt-4">
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        Guardar Evaluación
                    </button>
                    <button type="button" @click="openModal = false" 
                            class="ml-2 bg-gray-400 text-white px-4 py-2 rounded-lg hover:bg-gray-500">
                        Cancelar
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- ✅ MODAL DE CONFIRMACIÓN --}}
    <div x-show="showSuccess" 
         class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
         x-transition>
        <div class="bg-white p-6 rounded-xl shadow-xl text-center">
            <h3 class="text-xl font-semibold text-green-600 mb-3">✅ Evaluación creada correctamente</h3>
            <button @click="showSuccess = false; location.reload();" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                Cerrar
            </button>
        </div>
    </div>

    {{-- ✅ INPUT DE BÚSQUEDA --}}
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-lg font-semibold">🧾 Registro de Puntajes</h2>
        <input type="text" id="buscarAlumno" placeholder="Buscar alumno..." 
               class="border px-3 py-2 rounded-lg w-64 focus:ring focus:ring-blue-300" />
    </div>

    {{-- ✅ TABLA DE EVALUACIONES Y PUNTAJES --}}
    <div class="bg-white rounded-2xl shadow p-6 overflow-x-auto">
        @if($evaluaciones->isEmpty())
            <p class="text-gray-600 text-center">No hay evaluaciones registradas aún.</p>
        @else
        <table id="tablaEvaluaciones" class="min-w-full border-collapse text-sm">
            <thead>
                <tr class="bg-gray-50 text-gray-700 uppercase text-xs">
                    <th class="py-3 px-4 border-b">Alumno</th>
                    @foreach($evaluaciones as $eva)
                        <th class="py-3 px-4 border-b text-center">
                            {{ $eva->titulo }}
                            <div class="text-xs text-gray-500">
                                {{ ucfirst($eva->tipo) }}
                                @if($eva->semana) - Semana {{ $eva->semana }} @endif
                            </div>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($alumnos as $al)
                <tr class="hover:bg-gray-50 fila-alumno">
                    <td class="py-3 px-4 border-b font-medium nombre-alumno">
                        {{ $al->nombres }} {{ $al->apellidos }}
                    </td>
                    @foreach($evaluaciones as $eva)
                        @php
                            $nota = $notas->first(fn($n) => 
                                $n->id_alumno == $al->id_alumno && $n->evaluacion == $eva->id_evaluacion
                            );
                        @endphp
                        <td class="py-3 px-4 border-b text-center">
                            <input type="number" 
                                   value="{{ $nota->puntaje ?? '' }}" 
                                   class="border rounded px-2 py-1 w-16 text-center puntaje-input"
                                   data-alumno="{{ $al->id_alumno }}"
                                   data-evaluacion="{{ $eva->id_evaluacion }}"
                                   min="0" max="20" step="0.1">
                        </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>

        <div class="text-right mt-4">
            <button id="guardarTodo" 
                    class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                💾 Guardar Puntajes
            </button>
        </div>
        @endif
    </div>
</div>

{{-- ✅ SCRIPT DE FILTRO Y GUARDADO --}}
<script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
<script>
// 🔎 Filtro en tiempo real
document.getElementById('buscarAlumno').addEventListener('keyup', function() {
    const texto = this.value.toLowerCase();
    document.querySelectorAll('.fila-alumno').forEach(fila => {
        const nombre = fila.querySelector('.nombre-alumno').textContent.toLowerCase();
        fila.style.display = nombre.includes(texto) ? '' : 'none';
    });
});

// 💾 Guardar puntajes
document.getElementById('guardarTodo')?.addEventListener('click', async () => {
    const inputs = document.querySelectorAll('.puntaje-input');
    let cambios = 0;
    for (let input of inputs) {
        const id_alumno = input.dataset.alumno;
        const id_evaluacion = input.dataset.evaluacion;
        const puntaje = input.value.trim();
        if (puntaje === '') continue;
        cambios++;
        await fetch(`{{ route('tutor.evaluaciones.guardar') }}`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ id_alumno, id_evaluacion, puntaje })
        });
    }
    alert(cambios > 0 ? '✅ Puntajes guardados correctamente' : '⚠️ No hay cambios que guardar');
});
</script>
@endsection
