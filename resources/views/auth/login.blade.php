<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
</head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Caompatible" content="ie-edge">
<!-- Bootstrap-->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
<!-- Fuentes -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
    rel="stylesheet">
<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">
<!-- Estilos -->
@vite(['resources/sass/app.scss', 'resources/js/app.js'])
<!-- iCONOS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
<link rel="stylesheet" href="https://creativecommons.org/licenses/by/4.0/">

<title></title>

<body class="bg-login" style="background-image: url('{{ asset('images/bg-image.png') }}');">

    <div class="login container_form grid" id="loginAccessRegister">
        <!--===== FORM LOGIN =====-->

        <div class="form-container login-form" id="login-access">
            <div class="form-blob">
                <img src="{{ asset('images/blob.svg') }}" alt="blob" class="blob-image blob-image--1">
                <img src="{{ asset('images/blob.svg') }}" alt="blob" class="blob-image blob-image--2">
                <img src="{{ asset('images/blob.svg') }}" alt="blob" class="blob-image blob-image--3">
            </div>
            <div class="form-header">
                <h1>Arrax ERP</h1>
                <p>Bienvenido</p>
                <img src="{{ asset('images/logo5.png') }}" alt="logo">
            </div>
            <form method="POST" class="form-box" action="{{ route('login') }}">
                @csrf

                <!--===== INPUT EMAIL =====-->
                <div class="input-group">
                    <input id="email" type="email" class="input-field @error('email') is-invalid @enderror"
                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                    <label for="email" class="floating-label">Correo</label>
                    @error('email')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>

                <!--===== INPUT PASSWORD =====-->
                <div class="input-group">
                    <input type="password" id="password" class="input-field @error('password') is-invalid @enderror"
                        name="password" required autocomplete="current-password">
                    <label for="password" class="floating-label">Contraseña</label>
                    <div class="eye-icon" onclick="togglePassword()">
                        <i class="bi bi-eye"></i>
                    </div>
                    @error('password')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                    @enderror
                </div>


                <!--===== CHECK RECORDAR =====-->
                <div class="input-group checkbox-group">
                    <div class="form-col remember-me">
                        <input class="checkbox-field" type="checkbox" name="remember" id="remember"
                            {{ old('remember') ? 'checked' : '' }}>
                        <label for="remember-me-checkbox">Recordar sesión</label>
                    </div>
                </div>

                <!--===== BUTTON LOGIN =====-->
                <button type="submit" class="form-btn form-btn--submit">Iniciar Sesión</button>

            </form>
            <div class="form-divider">
                <p>Or</p>
            </div>
            <div class="form-bottom">
                <div class="form-social">
                    <button class="form-btn form-btn--social">
                        <img src="{{ asset('images/google.svg') }}" class="btn-icon" alt="google">
                    </button>
                    <button class="form-btn form-btn--social">
                        <img src="{{ asset('images/facebook-icon.svg') }}" class="btn-icon" alt="facebook">
                    </button>
                    <button class="form-btn form-btn--social">
                        <img src="{{ asset('images/twitter.svg') }}" class="btn-icon" alt="twiter">
                    </button>
                </div>
                <p>¿Olvidaste tú contraseña?
                    <a href="{{ route('register') }}" class="form-link" id="to-register">
                        Crear
                    </a>
                </p>
            </div>
        </div>
        <!--===== FORM RESTAURAR =====
        <div class="form-container reset-form" id="login-reset">
            <div class="form-blob">
                <img src="/img/blob.svg" alt="blob" class="blob-image blob-image--1">
                <img src="/img/blob.svg" alt="blob" class="blob-image blob-image--2">
                <img src="/img/blob.svg" alt="blob" class="blob-image blob-image--3">
            </div>
            <div class="form-header">
                <h1>Arrax ERP</h1>
                <img src="/img/logo5.png" alt="logo">
                <p class="reset-title">Solicitar contraseña</p>
            </div>
            <form action="" class="form-box">
                <div class="input-group">
                    <input type="text" id="text" class="input-field" placeholder="" required>
                    <label for="text" class="floating-label">Nombre completo</label>
                </div>
                <div class="input-group">
                    <input type="email" id="email" class="input-field" placeholder="" required>
                    <label for="email" class="floating-label">Correo</label>
                </div>
                <button type="submit" class="form-btn form-btn--submit">Enviar solicitud</button>
            </form>
            <div class="form-divider">
                <p><i class="bi bi-circle"></i></p>
            </div>
            <div class="form-bottom">
                <div class="from-description">
                    <p class="text-reset">
                        Si has olvidado tu contraseña, puedes solicitar una nueva
                        <br>
                        y te llegara un correo con tu nueva contraseña.
                    </p>
                </div>
                <p>¿Ya tienes una cuenta?<a href="#" class="form-link" id="to-login"> Inicia Sesión</a>
                </p>
            </div>
        </div>-->
    </div>








    <!-- JS  -->
    <script src="./js/main.js"></script>
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>

    @if (Route::has('login'))
        <div class="h-14.5 hidden lg:block"></div>
    @endif
</body>

</html>
