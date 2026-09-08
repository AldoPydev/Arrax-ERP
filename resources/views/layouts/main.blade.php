<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Arrax ERP</title>
    <!-- Bootstrap-->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Fuentes -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <!-- Estilos -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <!-- iCONOS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://creativecommons.org/licenses/by/4.0/">

</head>

<body class="bg-dashboard">

    <!--=============== HEADER ===============-->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <div class="logo">
                <img class="img-logo" src="{{ asset('images/logo5.png') }}" alt="img-idioma">
                <a class="text-titulo navbar-brand" href="#">ARRAX</a>
            </div>
            <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel">
                <div class="offcanvas-header">
                    <div class="logo-2">
                        <img class="img-logo-2" src="{{ asset('images/logo5.png') }}" alt="img-idioma">
                        <a class="text-subtitulo-2 navbar-brand" href="#">ARRAX</a>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-center flex-grow-1 pe-3">
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2 active" aria-current="page" href="">
                                <iconify-icon class="icon_nav" icon="solar:home-angle-2-outline"></iconify-icon>
                                Inicio
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2" href="{{ 'dashboard' }}">
                                <iconify-icon class="icon_nav" icon="solar:widget-outline"></iconify-icon>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2" href="#">
                                <iconify-icon class="icon_nav" icon="solar:letter-outline"></iconify-icon>
                                Correo
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2" href="#">
                                <iconify-icon class="icon_nav" icon="solar:chat-round-line-outline"></iconify-icon>
                                Mensajes
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link mx-lg-2" href="#">
                                <iconify-icon class="icon_nav" icon="solar:monitor-smartphone-outline"></iconify-icon>
                                Soporte Técnico
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
            <!--=============== AQUI VA PARTE DEL PERFIL ===============-->
            <div class="perfil">
                <div class="idioma">
                    <img class="img-idioma" src="{{ asset('images/mexico.png') }}" alt="img-idioma">
                    <iconify-icon class="barra-icon dropdown__arrow" icon="solar:alt-arrow-down-linear"></iconify-icon>
                </div>
                <div class="notificaciones d-flex align-items-center">
                    <iconify-icon class=" barra-icon icon-notificacion" icon="solar:bell-linear"></iconify-icon>
                </div>
                <div class="perfil-user">
                    <a href="#" class="dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown"
                        aria-expanded="false">
                        <div class="contenedor-perfil">
                            <iconify-icon class="barra-icon perfil-icon" icon="solar:user-linear"></iconify-icon>
                        </div>
                        <span class="nav-link">{{ Auth::user()->name }}</span>
                        <iconify-icon class="barra-icon dropdown__arrow"
                            icon="solar:alt-arrow-down-linear"></iconify-icon>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="dropdownUser1">
                        <li>
                            <a class="dropdown-item" href="#">
                                <iconify-icon class="barra-icon" icon="solar:user-bold"></iconify-icon>
                                <span>Mi Perfil</span>
                            </a>
                        </li>
                        <li>
                            <a class="dropdown-item" href="#">
                                <iconify-icon class="barra-icon" icon="solar:tuning-2-outline"></iconify-icon>
                                <span>Ajuste</span>
                            </a>
                        </li>
                        <li>
                            <hr class="dropdown-divider">
                        </li>
                        <li>
                            <form class="dropdown-item" action="{{ route('logout') }}" method="POST"
                                class="d-inline">
                                @csrf

                                <button type="submit" class="btn btn-link nav-link">
                                    <iconify-icon class="barra-icon" icon="solar:exit-linear"></iconify-icon>
                                    Cerrar sesion
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
            <!--=============== BOTON MENU ===============-->
            <button class="navbar-toggler pe-0" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>

    <!--=============== MAIN ===============-->

    <!-- MENSAJES DE ACCIONES CREADO -->
    @if (session('success'))
        <div class="alert alert-success fade show" id="alerta-success">
            {{ session('success') }}
        </div>
    @endif


    <!--=============== CONTENT ===============-->
    <div class="main_container">
        @yield('contenido')
    </div>

    <!--=============== FOOTER ===============-->
    <footer>
        <div class="social">
            <a href="#"><i class="bi bi-facebook"></i></a>
            <a href="#"><i class="bi bi-instagram"></i></a>
            <a href="#"><i class="bi bi-twitter-x"></i></a>
            <a href="#"><i class="bi bi-linkedin"></i></a>
        </div>
        <ul class="footer-list">
            <li><a href="#">Nosotros</a></li>
            <li><a href="#">Politicas de privacidad</a></li>
            <li><a href="#">Soporte Técnico</a></li>
            <li><a href="#">Contacto</a></li>
        </ul>
        <p class="Copyright">Copyright 2026 © Derechos reservados.
            <br>
            Aldo Ortiz Sánchez
        </p>
    </footer>

    <script src="../../js/main.js"></script>
    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>

    <script src="https://code.iconify.design/iconify-icon/2.1.0/iconify-icon.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>
