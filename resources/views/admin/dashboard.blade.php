@extends('app')

@section('content')
<div class="dashboard-container">
    <h1>Bienvenido, {{ Auth::user()->nombres }}</h1>
    <p>Este es tu panel de control docente.</p>
</div>
@endsection
