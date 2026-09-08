@extends('layouts.main')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- FORMULARIO  -->
                <div class="form_desing">

                    <!-- CABECERA  -->
                    <div>
                        <div class="form-header">
                            <a href="{{ route('admin.users.index') }}">
                                <iconify-icon class="icon-back" icon="solar:round-alt-arrow-left-bold"></iconify-icon>
                            </a>
                            <!-- FOTO DE PERFIL -->
                            <img class="bg-header" src="{{ asset('images/form-bg.jpg') }}" alt="">
                            <img class="logo-arrax" src="{{ asset('images/logo6.png') }}" alt="">
                            <span class="text_title">Nuevo Usuario</span>
                        </div>
                    </div>

                    <!-- ALERTA DE ERROR  -->
                    @if ($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert" id="alert-message">
                            <h6>Corregir el siguiente error</h6>

                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- CUERPO FORM  -->
                    <div class="form-body">
                        <form class="form_user_create mt-0" method="POST" action="{{ route('admin.users.store') }}">

                            <!-- METODO DE SEGURIDAD -->
                            @csrf

                            <span class="form-indicacion">Datos obligatorios * </span>
                            <br>

                            <!-- DATOS PERSONALES -->
                            <label for="name" class="text_subtitle sub_text mt-3 mb-3">Datos Personales</label>
                            <div class="user_content mb-3">

                                <div class="user_item">
                                    <label for="name">Nombre completo*</label>
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" required autocomplete="name" autofocus>

                                    <!-- ALERTA DE NOMBRE ERRONIO -->
                                    @error('name')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="user_item">
                                    <label for="correo">Correo Personal</label>
                                    <input id="correo" type="email" class="form-control" name="correo"
                                        value="{{ old('correo') }}" autocomplete="correo">
                                </div>

                            </div>

                            <div class="user_content mb-3">

                                <div class="user_item">
                                    <label for="telefono">Teléfono *</label>
                                    <input id="telefono" type="text" class="form-control" name="telefono" required
                                        autofocus>
                                    <span class="form-indicacion">10 digitos</span>

                                </div>

                                <div class="user_item">
                                    <label for="profesion">Profesión</label>
                                    <input id="profesion" type="text" class="form-control" name="profesion">
                                </div>

                            </div>

                            <!-- DATOS LABORALES -->
                            <label for="name" class="text_subtitle sub_text mb-3">Datos Laborales</label>
                            <div class="user_content mb-3">

                                <div class="user_item">
                                    <label for="empleado">Número de empleado *</label>
                                    <input id="empleado" type="text" class="form-control" name="empleado" required
                                        autofocus>
                                </div>

                                <div class="user_item">
                                    <label for="email">Correo Institucional *</label>
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') }}" required autocomplete="email">

                                    <!-- ALERTA DE EMAIL ERRONIO -->
                                    @error('email')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror

                                </div>

                            </div>

                            <div class="user_content mb-3">

                                <div class="user_item">
                                    <label for="password">Contraseña *</label>
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="new-password">
                                    <span class="form-indicacion">Mínimo 8 caracteries, letras y números</span>

                                    <!-- ALERTA DE PASSWORD ERRONIO -->
                                    @error('password')
                                        <span class="invalid-feedback" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror


                                </div>

                                <div class="user_item">
                                    <label for="password-confirm">Confirmar Contraseña *</label>
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" required autocomplete="new-password">
                                </div>

                            </div>

                            <!-- Sección de Permisos -->
                            <div class="form-group">
                                <label for="name" class="text_subtitle sub_text">Rol de usuario *</label>
                                <br>
                                <span class="form-indicacion" style="font-size: 10px">Seleccionar al menos un rol</span>
                                <div class="permissions-box mt-4 mb-4">
                                    <input type="checkbox" id="seleccionar-todos">
                                    <label for="selectAll" class="checkbox-label mb-4">Todos</label>

                                    <!-- Mostrar Permisos -->
                                    <div>
                                        <ul class="checkbox-grid">
                                            @foreach ($roles as $role)
                                                <li>
                                                    <input class="opcion" type="checkbox" name="roles[]"
                                                        value="{{ $role->id }}" @checked(in_array($role->id, old('roles', [])))>
                                                    <!-- @ checked recupera los check previo seleccionados despues de un error  -->
                                                    <span>{{ $role->name }}</span>
                                                    <!-- Nombre del permiso  -->
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>

                                </div>
                            </div>



                            <!-- Botones -->
                            <div class="form-bottons">
                                <button type="submit" class="btn btn-primary mr-2">Crear</button>
                                <a href="{{ route('admin.users.index') }}" class="btn btn-warning"> Cancelar</a>
                            </div>

                        </form>
                    </div>

                    <!-- OLAS DE FORM  -->
                    <div class="waves">
                        <svg class="wave-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 160"
                            preserveAspectRatio="none">
                            <!-- Ola trasera-->
                            <path fill="#e4effb" d="M0,64 C320,130 480,130 720,80 C960,30 1120,30 1440,64 V160 H0 V64 Z">
                            </path>

                            <!-- Ola frontal -->
                            <path fill="#cbe0fa" d="M0,80 C320,150 480,150 720,95 C960,40 1120,40 1440,80 V160 H0 V80 Z">
                            </path>
                        </svg>
                    </div>
                </div>
            </div>

        </div>
    </div>
@endsection
