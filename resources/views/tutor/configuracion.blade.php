@extends('app')

@section('content')
<div class="min-h-screen bg-gray-100 py-8 px-4">

    <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-xl p-8 border border-purple-100">

        <!-- Encabezado -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-3xl font-bold text-purple-700">Configuración de Cuenta - Tutor</h2>
        </div>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 px-4 py-2 rounded-lg mb-4 border border-green-200">
                ✅ {{ session('success') }}
            </div>
        @endif

        <!-- ✅ Cambiar la ruta del formulario -->
        <form action="{{ route('tutor.actualizar') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Sección Perfil -->
            <section class="mb-8">
                <h3 class="text-xl font-semibold text-purple-700 mb-4">Información Personal</h3>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8 items-center">

                    <!-- Foto del usuario -->
                    <div class="flex flex-col items-center gap-3">
                        <img 
                            src="{{ $usuario->foto ? asset($usuario->foto) : asset('img/image.png') }}" 
                            alt="Foto de perfil"
                            id="preview-foto"
                            class="w-36 h-36 rounded-full object-cover border-4 border-purple-400 shadow-md"
                        >

                        <label class="cursor-pointer">
                            <span class="bg-purple-600 hover:bg-purple-700 text-white px-4 py-1 rounded-lg text-sm">
                                Cambiar foto
                            </span>
                            <input type="file" name="foto" id="foto" class="hidden">
                        </label>
                    </div>

                    <!-- Datos del usuario -->
                    <div class="md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-5">

                        <div>
                            <label class="text-sm font-medium text-gray-700">Nombres</label>
                            <input type="text" name="nombres" value="{{ $usuario->nombres }}"
                                class="w-full border border-purple-200 rounded-lg px-3 py-2 mt-1 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Ej. Carlos Martín" required>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Apellidos</label>
                            <input type="text" name="apellidos" value="{{ $usuario->apellidos }}"
                                class="w-full border border-purple-200 rounded-lg px-3 py-2 mt-1 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="Ej. Fernández Torres" required>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Correo electrónico</label>
                            <input type="email" name="email" value="{{ $usuario->email }}"
                                class="w-full border border-purple-200 rounded-lg px-3 py-2 mt-1 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="ejemplo@correo.com" required>
                        </div>

                        <div>
                            <label class="text-sm font-medium text-gray-700">Teléfono (opcional)</label>
                            <input type="text" name="telefono" value="{{ $usuario->telefono }}"
                                class="w-full border border-purple-200 rounded-lg px-3 py-2 mt-1 focus:ring-purple-500 focus:border-purple-500"
                                placeholder="+51 999 888 777">
                        </div>

                    </div>
                </div>
            </section>

            <!-- Botones -->
            <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                <!-- ✅ Cambiar enlace para volver al perfil del tutor -->
                <a href="{{ route('tutor.perfil') }}"
                   class="px-5 py-2 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-lg shadow-sm transition">
                    Cancelar
                </a>

                <button type="submit"
                    class="px-6 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg shadow-md transition">
                    Guardar Cambios
                </button>
            </div>

        </form>
    </div>
</div>

<script>
document.getElementById('foto').addEventListener('change', e => {
    const file = e.target.files[0];
    if (file) {
        document.getElementById('preview-foto').src = URL.createObjectURL(file);
    }
});
</script>
@endsection
