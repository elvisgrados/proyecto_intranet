@extends('app')

@section('content')
  <title>Dashboard Docente — Academia Medallón</title>

  {{-- Enlace a tu CSS personalizado --}}

  <div class="layout dark-mode">

    <!-- CONTENIDO -->
    <main class="main-content">
      <header class="topbar">
        <h1>Dashboard Docente</h1>
        <div class="user-info">
          <div>
            <strong>{{ Auth::user()->nombres }} {{ Auth::user()->apellidos }} </strong>
            <p>{{ Auth::user()->email }}</p>
          </div>
          @if(Auth::user()->foto && file_exists(public_path(Auth::user()->foto)))
              <img src="{{ asset(Auth::user()->foto) }}" alt="Perfil" class="avatar">
          @else
              <img src="{{ asset('img/default-avatar.png') }}" alt="Perfil" class="avatar">
          @endif
        </div>
      </header>

      <section class="cards">
        <div class="card">
          <h3>Clases hoy</h3>
          <p class="count">3</p>
          <span>08:00 - 14:00</span>
        </div>

        <div class="card">
          <h3>Alumnos (total)</h3>
          <p class="count">128</p>
          <span>5 cursos activos</span>
        </div>

        <div class="card warning">
          <h3>Evaluaciones por corregir</h3>
          <p class="count">6</p>
          <span>Última recepción: 11 oct 2025</span>
        </div>

        <div class="card info">
          <h3>Mensajes nuevos</h3>
          <p class="count">4</p>
          <span>2 comunicados administrativos</span>
        </div>
      </section>

      <footer class="footer">
        © 2025 Academia Medallón · Panel docente
      </footer>
    </main>
  </div>


@endsection 