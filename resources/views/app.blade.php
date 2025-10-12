<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academia Medallón</title>

    {{-- Enlace a tu CSS general --}}
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">
    {{-- Enlace al navbar moderno --}}
    <link rel="stylesheet" href="{{ asset('css/navbar.css') }}">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

</head>
<body>

    {{-- ✅ Navbar tipo glass --}}
    <nav class="navbar">
        <div class="logo"><img src="{{asset('logoblanco.png')}}" alt=""></div>
        <ul class="nav-links">
            @auth
                @if(Auth::user()->id_tipo === 2)
                    <li><a href="{{ route('docente.dashboard') }}">Panel Docente</a></li>
                    <li><a href="{{ route('docente.horario') }}">Horario</a></li>
                    <li><a href="{{ route('docente.cursos') }}">Cursos</a></li>
                    <li><a href="{{ route('docente.comunicaciones') }}">Comunicaciones</a></li>
                    <li><a href="{{ route('docente.informes') }}">Informes</a></li>
                @elseif(Auth::user()->id_tipo === 3)
                    <li><a href="{{ route('alumno.dashboard') }}">Panel Alumno</a></li>
                    <li><a href="{{ route('alumno.horario') }}">Horario</a></li>
                    <li><a href="{{ route('alumno.cursos') }}">Cursos</a></li>
                    <li><a href="{{ route('alumno.pagos') }}">Pagos</a></li>
                    <li><a href="{{ route('alumno.resultados') }}">Resultados</a></li>
                @endif

                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit">Cerrar sesión</button>
                    </form>
                </li>
            @else
                <li><a href="{{ route('login') }}">Iniciar sesión</a></li>
            @endauth
        </ul>
    </nav>

    {{-- Contenido principal de cada vista --}}
    <main>
        @yield('content')
    </main>

</body>
</html>
