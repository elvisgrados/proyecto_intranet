@extends('layouts.alumno')

@section('content')
<div style="padding: 30px;">
    <h2>Perfil del alumno</h2>
    <p><strong>Nombre:</strong> {{ $user->nombres }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>
</div>
@endsection
