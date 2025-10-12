<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academia Medallón</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="{{ url('/') }}">Inicio</a></li>

                {{-- 🔹 Menú para alumnos --}}
                @if(Auth::check() && Auth::user()->rol == 'alumno')
                    <li><a href="{{ route('alumno.dashboard') }}">Panel Alumno</a></li>
                    <li><a href="{{ route('alumno.horario') }}">Horario</a></li>
                    <li><a href="{{ route('alumno.cursos') }}">Cursos</a></li>
                    <li><a href="{{ route('alumno.pagos') }}">Pagos</a></li>
                @endif

                {{-- 🔹 Menú para docentes --}}
                @if(Auth::check() && Auth::user()->rol == 'docente')
                    <li><a href="{{ route('docente.dashboard') }}">Panel Docente</a></li>
                    <li><a href="{{ route('docente.horario') }}">Horario</a></li>
                    <li><a href="{{ route('docente.cursos') }}">Cursos</a></li>
                    <li><a href="{{ route('docente.comunicaciones') }}">Comunicaciones</a></li>
                    <li><a href="{{ route('docente.informes') }}">Informes</a></li>
                @endif

                {{-- 🔹 Cerrar sesión --}}
                @if(Auth::check())
                    <li>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit">Salir</button>
                        </form>
                    </li>
                @endif
            </ul>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>
</body>
</html>
