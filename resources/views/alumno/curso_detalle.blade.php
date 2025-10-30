@extends('app')

@section('content')
<div class="container my-4 position-relative">

 <div class="row">
  <!-- 📚 Sección principal -->
  <div class="col-md-8">
   <div class="card shadow-sm mb-3">
    <div class="card-body">
     <h4 class="fw-bold text-primary">{{ $curso->nombre_curso }}</h4>
     <p><strong>Docente:</strong> {{ $curso->docente }}</p>
    </div>
   </div>

   <!-- 👩‍🏫 Temas -->
   <div class="card shadow-sm mb-3">
    <div class="card-body">
     <h5 class="text-secondary mb-3">📅 Temas por Semana</h5>
     <table class="table table-sm">
      <thead>
       <tr>
        <th>Semana</th>
        <th>Tema</th>

       </tr>
      </thead>
      <tbody>
       @foreach($temas as $tema)
       <tr>
        <td>{{ $tema['semana'] }}</td>
        <td>{{ $tema['tema'] }}</td>

       </tr>
       @endforeach
      </tbody>
     </table>
    </div>
   </div>

   <!-- 💻 Enlace y examen -->
   @if($enlaceClase)
   <div class="card shadow-sm p-3 mb-3">
    <p><strong>Enlace de clase:</strong>
     <a href="{{ $enlaceClase }}" target="_blank">Acceder a clase virtual</a>
    </p>

   </div>

   @endif

   <!-- 🔽 Secciones dinámicas -->
   <div id="panel-dinamico" class="mt-4"></div>
  </div>

  <!-- 📋 Panel lateral -->
  <div class="col-md-4">
   <div class="card shadow-sm p-3">
    <h6 class="text-secondary mb-3">Opciones del curso</h6>
    <div class="list-group">
     <a href="#" class="list-group-item list-group-item-action d-flex align-items-center"
       onclick="mostrarSeccion('participantes')">
      <i class="fa fa-users me-2 text-primary"></i> Ver a los participantes de su curso
     </a>
     <a href="#" class="list-group-item list-group-item-action d-flex align-items-center"
       onclick="mostrarSeccion('asistencia')">
      <i class="fa fa-calendar-check me-2 text-success"></i> Ver su asistencia
     </a>

     <a href="#" class="list-group-item list-group-item-action d-flex align-items-center"

       onclick="mostrarSeccion('material')">

      <i class="fa fa-book-open me-2 text-warning"></i> Material de apoyo

     </a>

     <a href="#" class="list-group-item list-group-item-action d-flex align-items-center"

       onclick="mostrarSeccion('mensajes')">

      <i class="fa fa-envelope me-2 text-info"></i> Mensajes

     </a>

    </div>

   </div>

  </div>

 </div>

</div>



<script>

 function mostrarSeccion(seccion) {

  const contenedor = document.getElementById('panel-dinamico');

  let html = '';



  if (seccion === 'participantes') {

   html = `

    <div class="card shadow-sm mb-3">

     <div class="card-body">

      <h5 class="text-secondary">👥 Participantes del curso</h5>

      <ul class="list-group mt-2">

       @foreach($alumnos as $a)

        <li class="list-group-item">{{ $a['nombre'] }}</li>

       @endforeach

      </ul>

     </div>

    </div>

   `;

  }



  if (seccion === 'asistencia') {

   html = `

    <div class="card shadow-sm mb-3">

     <div class="card-body">

      <h5 class="text-secondary">📅 Asistencia</h5>

      <p>Tu registro de asistencia se mostrará aquí próximamente.</p>

     </div>

    </div>

   `;

  }



  if (seccion === 'material') {

   html = `

    <div class="card shadow-sm mb-3">

     <div class="card-body">

      <h5 class="text-secondary">📘 Material de apoyo</h5>

      <ul>

       <li><a href="https://drive.google.com/drive/folders/1dlvptM3aX5kUrY2RQpyR8_v8LJCFDfaJ">Guía Semana 1</a></li>

       <li><a href="https://drive.google.com/drive/folders/1dlvptM3aX5kUrY2RQpyR8_v8LJCFDfaJ">Presentación de clase</a></li>

       <li><a href="https://drive.google.com/drive/folders/1dlvptM3aX5kUrY2RQpyR8_v8LJCFDfaJ">Ejercicios de práctica</a></li>

      </ul>

     </div>

    </div>

   `;

  }



   // 💬 Mensajes

   else if (seccion === 'mensajes') {

   html = `

    <div class="card shadow-sm">

     <div class="card-body">

      <div class="d-flex justify-content-between align-items-center">

       <h5 class="text-secondary mb-0">💬 Mensajes del curso</h5>

       <button class="btn btn-sm btn-outline-danger" id="cerrarSeccion"><i class="fa fa-times"></i></button>

      </div>

      <div class="mt-3">

       <p><strong>Docente:</strong> Bienvenidos al curso, cualquier duda me escriben aquí.</p>

       <div class="mt-3">

        <textarea class="form-control mb-2" rows="2" placeholder="Escribe un mensaje..."></textarea>

        <button class="btn btn-primary btn-sm"><i class="fa fa-paper-plane"></i> Enviar</button>

       </div>

      </div>

     </div>

    </div>

   `;

   }



  contenedor.innerHTML = html;

  contenedor.scrollIntoView({ behavior: 'smooth' });

 }

</script>

@endsection