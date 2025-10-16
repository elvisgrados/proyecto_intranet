<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Perfil del Alumno</title>
    <link rel="stylesheet" href="{{ asset('css/perfil.css') }}">
</head>
<body>

    <!-- Navbar -->
    <div class="navbar">
        <!-- Lado Izquierdo -->
        <div class="navbar-left">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" class="logo">
        <div class="navbar-menu">
        <a href="{{ route('alumno.cursos') }}">Cursos</a>
        <a href="{{ route('alumno.horario') }}">Horarios</a>
        <a href="{{ route('alumno.resultados') }}">Resultados de evaluación</a>
        <a href="{{ route('alumno.pagos') }}">Estado de cuenta</a>
        </div>
    </div>


     

       <div class="user-dropdown">
   
        <button class="user-btn">
            <img src="{{ asset('img/perfil_m.jpeg') }}" alt="Perfil">
            <span>{{ Auth::user()->nombres ?? 'Usuario' }}</span>
            <i class="fa fa-chevron-down"></i>
        </button>

        <div class="dropdown-menu">
            <a href="{{ route('perfil') }}">Ver perfil</a>
            <a href="{{ route('password.change') }}">Cambiar contraseña</a>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit">Cerrar sesión</button>
            </form>
        </div>
        </div>
    </div>

    <!-- MODAL PERFIL -->
<div id="modalPerfil" class="modal-perfil">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Configuración del perfil</h3>
        <p class="subtitulo">Cambiar imagen de perfil</p>

        <div class="avatar-container">
            <img id="imagenPerfil" src="{{ asset('img/perfil_m.jpeg') }}" alt="Avatar">
        </div>

        <label class="upload-btn">
            Cargar nueva imagen de perfil
            <input type="file" id="inputImagenPerfil" accept="image/*">
        </label>
    </div>
</div>


    



    <!-- 🧩 Aquí se mostrará el contenido de cada vista -->
    <main class="content">
        @yield('content')
    </main>
    <script>
document.addEventListener("DOMContentLoaded", () => {
    const modal = document.getElementById("modalPerfil");
    const verPerfil = document.querySelector(".dropdown-menu a[href='{{ route('perfil') }}']");
    const closeBtn = document.querySelector(".close");

    // Evitar redirección y mostrar modal
    verPerfil.addEventListener("click", (e) => {
        e.preventDefault();
        modal.style.display = "flex";
    });

    closeBtn.addEventListener("click", () => {
        modal.style.display = "none";
    });

    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });
});
</script>


</body>

</html>
