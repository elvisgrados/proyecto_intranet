@extends('layouts.alumno') {{-- usa tu layout alumno.blade --}}

@section('content')
<div class="perfil-container">
    <h2>Editar Perfil</h2>

    {{-- Mensaje de éxito --}}
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="perfil-foto">
        <img src="{{ $user->foto ? asset('storage/fotos/' . $user->foto) : asset('img/perfil_m.jpeg') }}" alt="Foto de perfil">
    </div>

    <form action="{{ route('perfil.foto') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <label for="foto">Cambiar foto:</label>
        <input type="file" name="foto" id="foto" accept="image/*" required>

        <button type="submit" class="btn-actualizar">Actualizar Foto</button>
    </form>
</div>

<style>
.perfil-container {
    text-align: center;
    margin-top: 50px;
}

.perfil-foto img {
    width: 150px;
    height: 150px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid #6c02e3;
}

.btn-actualizar {
    background-color: #6c02e3;
    color: white;
    border: none;
    padding: 10px 20px;
    margin-top: 15px;
    cursor: pointer;
    border-radius: 5px;
}

.btn-actualizar:hover {
    background-color: #5301b8;
}
</style>
@endsection
