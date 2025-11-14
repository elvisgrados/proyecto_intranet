<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Academia Medallón</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="//unpkg.com/alpinejs" defer></script>
    <link rel="icon" type="image/x-icon" href="{{ asset('iconmeda.png') }}">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        [x-cloak] { display: none !important; }

        /* Evita scroll global, pero permite que el área de contenido tenga su propio scroll */
        html, body {
            height: 100%;
            overflow: hidden;
        }

        /* Hace que la parte principal (donde está el yield) pueda desplazarse */
        main {
            height: 100vh;               /* ocupa toda la pantalla */
            overflow-y: auto;            /* activa scroll interno */
            flex: 1;                     /* se adapta al espacio disponible */
        }
    </style>
</head>
    <body class="bg-gray-100 text-gray-800">

    <div x-data="{ open: false }" class="flex h-screen">

        <!-- SIDEBAR -->
        <aside  
            :class="open ? 'translate-x-0' : '-translate-x-full'"
            class="fixed inset-y-0 left-0 z-30 w-64 text-white transform transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-0 flex flex-col"
            style="background: linear-gradient(180deg, #5b3cc4, #7a5af5);"
        >
            <!-- LOGO CENTRADO -->
            <div class="flex flex-col items-center justify-center py-6 border-b border-white/20">
                <a href="/" class="flex flex-col items-center">
                    <img src="{{ asset('logoblanco.png') }}" alt="Logo" class="w-26 h-20 mb-2">
                    <h2 class="text-lg font-semibold">Academia Medallón</h2>
                </a>

                @if(Auth::check())
                    <p class="text-sm text-white/80 mt-1">
                        @switch(Auth::user()->id_tipo)
                            @case(1) Administrador @break
                            @case(2) Docente @break
                            @case(3) Alumno @break
                            @case(4) Tutor @break
                            @default Usuario
                        @endswitch
                    </p>
                @endif
            </div>

            <!-- NAV LINKS -->
            <nav class="flex-1 p-5 space-y-2 text-sm font-medium overflow-y-auto">
                @auth
                    {{-- DOCENTE --}}
                    @if(Auth::user()->id_tipo === 2)
                        <a href="{{ route('docente.cursos') }}" class="block px-3 py-2 rounded transition 
                                {{ request()->routeIs('docente.cursos*') ? 'bg-white/30 font-semibold text-white shadow-sm' : 'hover:bg-white/20' }}">
                            📚 Cursos
                        </a>

                        <a href="{{ route('docente.horario') }}" class="block px-3 py-2 rounded transition 
                                {{ request()->routeIs('docente.horario*') ? 'bg-white/30 font-semibold text-white shadow-sm' : 'hover:bg-white/20' }}">
                            🗓️ Horario
                        </a>

                        <a href="{{ route('evaluaciones.general') }}" class="block px-3 py-2 rounded transition 
                                {{ request()->routeIs('docente.evaluaciones*') ? 'bg-white/30 font-semibold text-white shadow-sm' : 'hover:bg-white/20' }}">
                            📝 Evaluaciones
                        </a>

                        <a href="{{ route('docente.perfil') }}"
                        class="block px-3 py-2 rounded transition 
                                {{ request()->routeIs('docente.perfil*') ? 'bg-white/30 font-semibold text-white shadow-sm' : 'hover:bg-white/20' }}">
                            ⚙️ Perfil
                        </a>
                    @endif

                    {{-- ALUMNO --}}
                    @if(Auth::user()->id_tipo === 3)
                        <a href="{{ route('alumno.dashboard') }}"
                        class="block px-3 py-2 rounded transition 
                                {{ request()->routeIs('alumno.dashboard*') ? 'bg-white/30 font-semibold text-white shadow-sm' : 'hover:bg-white/20' }}">
                            🏠 Panel
                        </a>

                        <a href="{{ route('alumno.cursos') }}"
                        class="block px-3 py-2 rounded transition 
                                {{ request()->routeIs('alumno.cursos*') ? 'bg-white/30 font-semibold text-white shadow-sm' : 'hover:bg-white/20' }}">
                            📘 Cursos
                        </a>

                        <a href="{{ route('alumno.horario') }}"
                        class="block px-3 py-2 rounded transition 
                                {{ request()->routeIs('alumno.horario*') ? 'bg-white/30 font-semibold text-white shadow-sm' : 'hover:bg-white/20' }}">
                            🗓️ Horarios
                        </a>

                        <a href="{{ route('alumno.resultados') }}"
                        class="block px-3 py-2 rounded transition 
                                {{ request()->routeIs('alumno.resultados*') ? 'bg-white/30 font-semibold text-white shadow-sm' : 'hover:bg-white/20' }}">
                            📊 Resultados
                        </a>

                        <a href="{{ route('alumno.pagos') }}"
                        class="block px-3 py-2 rounded transition 
                                {{ request()->routeIs('alumno.pagos*') ? 'bg-white/30 font-semibold text-white shadow-sm' : 'hover:bg-white/20' }}">
                            💰 Estado Cuenta
                        </a>

                        <a href="{{ route('alumno.perfil') }}"
                        class="block px-3 py-2 rounded transition 
                                {{ request()->routeIs('alumno.perfil*') ? 'bg-white/30 font-semibold text-white shadow-sm' : 'hover:bg-white/20' }}">
                            ⚙️ Configuración
                        </a>
                    @endif

                    {{-- TUTOR --}}
                    @if(Auth::user()->id_tipo === 4)
                        <a href="{{ route('tutor.horario') }}"
                        class="block px-3 py-2 rounded transition 
                                {{ request()->routeIs('tutor.horario*') ? 'bg-white/30 font-semibold text-white shadow-sm' : 'hover:bg-white/20' }}">
                            🗓️ Horario
                        </a>

                        <a href="{{ route('asistencia.index') }}"
                        class="block px-3 py-2 rounded transition 
                                {{ request()->routeIs('asistencia.index*') ? 'bg-white/30 font-semibold text-white shadow-sm' : 'hover:bg-white/20' }}">
                            📝 Asistencia
                        </a>

                        <a href="{{ route('tutor.evaluaciones') }}"
                        class="block px-3 py-2 rounded transition 
                                {{ request()->routeIs('tutor.evaluaciones*') ? 'bg-white/30 font-semibold text-white shadow-sm' : 'hover:bg-white/20' }}">
                            📝 Evaluaciones
                        </a>

                        <a href="{{ route('tutor.informes') }}"
                        class="block px-3 py-2 rounded transition 
                                {{ request()->routeIs('tutor.informes*') ? 'bg-white/30 font-semibold text-white shadow-sm' : 'hover:bg-white/20' }}">
                            📈 Informes
                        </a>

                        <a href="{{ route('tutor.perfil') }}"
                        class="block px-3 py-2 rounded transition 
                                {{ request()->routeIs('tutor.perfil*') ? 'bg-white/30 font-semibold text-white shadow-sm' : 'hover:bg-white/20' }}">
                            ⚙️ Perfil
                        </a>
                    @endif

                    {{-- LOGOUT --}}
                    <form action="{{ route('logout') }}" method="POST" class="mt-4 border-t border-white/20 pt-4">
                        @csrf
                        <button type="submit" class="w-full text-left px-3 py-2 rounded hover:bg-white/20 transition">
                            🚪 Cerrar sesión
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="block px-3 py-2 rounded hover:bg-white/20 transition">Iniciar sesión</a>
                @endauth
            </nav>
        </aside>

        <!-- CONTENIDO PRINCIPAL -->
        <div class="flex-1 flex flex-col">
            <!-- HEADER MOBILE -->
            <header class="bg-white shadow-md flex items-center justify-between p-4 md:hidden">
                <button @click="open = true" class="text-gray-700 hover:text-purple-600 text-2xl">
                    ☰
                </button>
                <h1 class="font-bold text-lg text-gray-700">Academia Medallón</h1>
            </header>

            <!-- MAIN CONTENT -->
            <main class="flex-1 p-6">
                @yield('content')
                @yield('scripts')
            </main>
        </div>
    </div>

    </body>
</html>
