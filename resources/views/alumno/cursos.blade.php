@extends('app')

@section('content')

<div class="main-content">

  <h1 class="titulo-pagina">🎓 Mis Cursos</h1>

  <div class="container my-4">
    <!-- 🏫 TÍTULO -->
    <h2 class="titulo-general">CICLO VERANO - PRESENCIAL</h2>

    <div class="d-flex flex-wrap justify-content-center align-items-center gap-3">

       <!-- 🔽 Dropdown dinámico -->
<div class="dropdown">
  <a class="btn btn-outline-primary dropdown-toggle px-4" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
    Seleccionar periodo
  </a>

  <ul class="dropdown-menu shadow-sm">
    @foreach ($periodos as $p)
      <li>
        <a 
          class="dropdown-item" 
          href="{{ route('alumno.cursos', ['id_periodo' => $p->id_periodo]) }}"
        >
          {{ $p->nombre }}
        </a>
      </li>
    @endforeach
  </ul>
</div>



      <!-- 🔍 Buscador -->
      <div class="buscador">
        <form action="{{ route('alumno.cursos') }}" method="GET" id="formBuscarCursos" class="d-flex">
          <input 
            type="text" 
            name="busqueda" 
            class="form-control rounded-start-pill px-3" 
            placeholder="Buscar cursos..." 
            value="{{ request('busqueda') }}"
            style="min-width: 250px;"
          >
          <button type="submit" class="btn btn-primary rounded-end-pill px-3">
            <i class="fa fa-search"></i>
          </button>
        </form>
      </div>

    </div>
  </div>

  <!-- 📚 LISTA DE CURSOS -->
  <div class="cursos-container">
    @foreach ($cursos as $curso)
      <div class="curso-card">
        <!-- Estado -->
      

        <!-- Nombre -->
        <h3 class="curso-titulo">{{ $curso->nombre_curso }}</h3>

        <!-- Progreso -->
        <div class="progress-container">
          <div class="progress-bar" style="width: {{ $curso->progreso ?? 0 }}%"></div>
        </div>
        <p class="progress-text">{{ $curso->progreso ?? 0 }}% Completado</p>

        <!-- Extra -->
        <p class="categoria">Categoría: {{ $curso->nombre_curso }}</p>
        <p class="docente">Docente: {{ $curso->nombre_docente }}</p>
      </div>
    @endforeach
  </div>

</div>

@endsection
