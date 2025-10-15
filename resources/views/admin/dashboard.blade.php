@extends('app')

@section('content')
<div class="dashboard-container">
    <h1>Bienvenid@, {{ Auth::user()->nombres }}</h1>
    <p>Este es tu panel de control administrador.</p>
</div>
@endsection
