<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio de Sesión</title>

    {{-- Enlace a tu CSS --}}
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">

    {{-- Íconos --}}
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <div class="wrapper">
        <h1>Iniciar Sesión</h1>

        {{-- Mostrar errores de validación --}}
        @if ($errors->any())
            <div class="alerta-error">
                <i class="fas fa-exclamation-circle"></i> {{ $errors->first() }}
            </div>
        @endif

        {{-- Mostrar mensaje de acceso no autorizado --}}
        @if (session('error'))
            <div class="alerta-error">
                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('login.post') }}">
            @csrf

            <div class="input-box">
                <input type="email" name="email" placeholder="Correo electrónico" value="{{ old('email') }}" required autofocus>
                <i class="fas fa-envelope"></i>
            </div>

            <div class="input-box">
                <input type="password" name="password" placeholder="Contraseña" required>
                <i class="fas fa-lock"></i>
            </div>

            <div class="remember-forgot">
                <label>
                    <input type="checkbox" name="remember"> Recordarme
                </label>
                <a href="#">¿Olvidaste tu contraseña?</a>
            </div>

            <button type="submit" class="btn">Ingresar</button>
        </form>
    </div>
</body>
</html>
