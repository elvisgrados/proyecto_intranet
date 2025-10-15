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
                        <a href="{{ route('alumno.dashboard') }}">🏠 Panel</a>
                        <a href="{{ route('alumno.horario') }}">🗓️ Horario</a>
                        <a href="{{ route('alumno.cursos') }}">📘 Cursos</a>
                        <a href="{{ route('alumno.pagos') }}">💰 Pagos</a>
                        <a href="{{ route('alumno.resultados') }}">📊 Resultados</a>
                    @endif

                    <form action="{{ route('logout') }}" method="POST" class="logout-form">
                        @csrf
                        <button type="submit">Cerrar sesión</button>
                    </form>
                @else
                    <a href="{{ route('login') }}">Iniciar sesión</a>
                @endauth
            </nav>

            <div class="sidebar-footer">
                <small>Última conexión:<br>{{ now()->format('d M Y, H:i') }}</small>
            </div>
        </aside>

        {{-- ✅ CONTENIDO PRINCIPAL --}}
        <main class="main-content">
            @yield('content')
        </main>
    </div>
</body>
</html>
