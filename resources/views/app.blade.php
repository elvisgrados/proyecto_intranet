<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academia Medallón</title>

    {{-- CSS principal y del sidebar --}}
    <link rel="stylesheet" href="{{ asset('css/docente/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/docente/configuracion.css') }}">
    <link rel="stylesheet" href="{{ asset('css/docente/cursos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/sidevar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/alumno/cursos.css') }}">
    <link rel="stylesheet" href="{{ asset('css/alumno/horario.css') }}">
    <link rel="stylesheet" href="{{ asset('css/alumno/resultados.css') }}">

 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body>
    <div class="app-container">
        {{-- ✅ SIDEBAR --}}
        <aside class="sidebar">
            <div class="sidebar-header">
                <img src="{{ asset('logoblanco.png') }}" alt="Logo" class="logo">
                @if(Auth::check())
                    @if(Auth::user()->id_tipo === 1)
                        <p>Administrador</p>
                    @elseif(Auth::user()->id_tipo === 2)
                        <p>Docente</p>
                    @elseif(Auth::user()->id_tipo === 3)
                        <p>Alumno</p>
                    @endif
                @endif
            </div>

            <nav class="sidebar-nav">
                @auth
                    @if(Auth::user()->id_tipo === 2)
                        <a href="{{ route('docente.dashboard') }}">🏠 Panel</a>
                        <a href="{{ route('docente.cursos') }}">📚 Cursos</a>
                        <a href="{{ route('docente.horario') }}">🗓️ Horario</a>
                        <a href="{{ route('docente.asistencia') }}">📝 asistencia</a>
                        <a href="{{ route('docente.evaluaciones') }}">📝 Evaluaciones</a>
                        <a href="{{ route('docente.comunicaciones') }}">💬 Comunicaciones</a>
                        <a href="{{ route('docente.informes') }}">📈 Informes</a>
                        <a href="{{ route('docente.configuracion') }}">⚙️ Configuración</a>
                    @elseif(Auth::user()->id_tipo === 3)
                        <a href="{{ route('alumno.dashboard') }}">🏠 Datos Usuario</a>
                        <a href="{{ route('alumno.cursos') }}">📘 Cursos</a>
                        <a href="{{ route('alumno.horario') }}">🗓️ Horarios</a>
                        <a href="{{ route('alumno.resultados') }}">📊 Resultados</a>
                        <a href="{{ route('alumno.pagos') }}">💰 Estado Cuenta</a>
                       
                    @endif

                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit">Cerrar sesión</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Iniciar sesión</a>
                @endauth
            </nav>
        </aside>

        {{-- ✅ CONTENIDO PRINCIPAL --}}
        <main class="main-content">
            @yield('content')
        </main>
    </div>
</body>
</html>
