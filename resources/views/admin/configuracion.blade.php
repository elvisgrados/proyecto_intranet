@extends('app')

@section('content')

<!-- ===== ESTILOS INLINE ===== -->
<style>
/* ===== CONTENEDOR ===== */
.profile-container {
    max-width: 900px;
    margin: 30px auto;
    padding: 25px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    display: flex;
    flex-direction: column;
    gap: 25px;
}

/* ===== HEADER ===== */
.profile-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.profile-header h1 {
    font-size: 1.8rem;
    font-weight: 700;
    color: #4a2da0;
}

/* ===== FORMULARIOS ===== */
.form-group {
    margin-bottom: 15px;
}
.form-group label {
    display: block;
    font-weight: 600;
    margin-bottom: 6px;
    color: #111827;
}
.form-group input, .form-group select {
    width: 100%;
    padding: 10px 12px;
    border: 1px solid #ccc;
    border-radius: 6px;
    font-size: 0.95rem;
    transition: 0.3s ease;
}
.form-group input:focus, .form-group select:focus {
    border-color: #4a2da0;
    outline: none;
}

/* ===== BOTONES ===== */
.btn {
    padding: 10px 20px;
    background: #4a2da0;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.3s ease;
}
.btn:hover {
    background: #3b2390;
}

/* ===== SECCIONES ===== */
.section {
    background: #f9f9ff;
    padding: 20px;
    border-radius: 10px;
}
.section h2 {
    margin-bottom: 15px;
    color: #4a2da0;
}

/* RESPONSIVE */
@media(max-width:768px){
    .profile-container { padding: 20px; }
}
</style>

<!-- ===== CONTENEDOR ===== -->
<div class="profile-container">

    <!-- HEADER -->
    <div class="profile-header">
        <h1>Configuración de Perfil</h1>
    </div>

    <!-- INFORMACIÓN PERSONAL -->
    <div class="section">
        <h2>Información Personal</h2>
        <form method="POST" action="{{ route('admin.configuracion.update') }}">
            @csrf
            <div class="form-group">
                <label>Nombre</label>
                <input type="text" name="nombres" value="{{ $usuario->nombres ?? '' }}" required>
            </div>
            <div class="form-group">
                <label>Apellidos</label>
                <input type="text" name="apellidos" value="{{ $usuario->apellidos ?? '' }}" required>
            </div>
            <div class="form-group">
                <label>DNI</label>
                <input type="text" name="dni" value="{{ $usuario->dni ?? '' }}" required>
            </div>
            <div class="form-group">
                <label>Correo Electrónico</label>
                <input type="email" name="email" value="{{ $usuario->email ?? '' }}" required>
            </div>
            <div class="form-group">
                <label>Teléfono</label>
                <input type="text" name="telefono" value="{{ $usuario->telefono ?? '' }}">
            </div>
            <button type="submit" class="btn">Actualizar Perfil</button>
        </form>
    </div>

    <!-- CAMBIAR CONTRASEÑA -->
    <div class="section">
        <h2>Cambiar Contraseña</h2>
        <form method="POST" action="{{ route('admin.configuracion.password') }}">
            @csrf
            <div class="form-group">
                <label>Contraseña Actual</label>
                <input type="password" name="current_password" required>
            </div>
            <div class="form-group">
                <label>Nueva Contraseña</label>
                <input type="password" name="new_password" required>
            </div>
            <div class="form-group">
                <label>Confirmar Nueva Contraseña</label>
                <input type="password" name="new_password_confirmation" required>
            </div>
            <button type="submit" class="btn">Actualizar Contraseña</button>
        </form>
    </div>

</div>

@endsection
