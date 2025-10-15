@extends('app')

@section('content')

<div class="main-content">
    <h2 class="titulo">⚙️ Configuración de Cuenta</h2>

    @if(session('success'))
        <div class="alert-success">{{ session('success') }}</div>
    @endif

    <div class="config-card">
        <form action="{{ route('docente.actualizar') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <!-- Perfil -->
            <section class="config-section">
                <h3>Perfil</h3>
                <div class="config-grid">
                    <div class="foto-perfil">
                        <img 
                        src="{{ $usuario->foto ? asset($usuario->foto) : asset('img/image.png') }}" 
                        alt="Foto de perfil" 
                        class="foto-perfil"
                        id="preview-foto">
                    </div>

                    <div class="info-form">
                        <input type="file" name="foto" id="foto" class="input-foto">

                        <label>Nombre completo</label>
                        <input type="text" name="nombres" value="{{ $usuario->nombres }}" placeholder="Ej. Carlos Fernández">

                        <label>Correo electrónico</label>
                        <input type="email" name="email" value="{{ $usuario->email }}" placeholder="ejemplo@correo.com">

                        <label>Teléfono (opcional)</label>
                        <input type="text" name="telefono" value="{{ $usuario->telefono }}" placeholder="+51 999 888 777">
                    </div>
                </div>
            </section>

            <hr>


            <div class="acciones">
                <button type="submit" class="btn-guardar">💾 Guardar cambios</button>
                <button type="reset" class="btn-cancelar">Cancelar</button>
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
