@extends('layouts.main')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- ALERTA DE ERROR  -->
                <div class="alert-content d-flex justify-end">
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
                </div>

                <!-- FORMULARIO  -->
                <div class="form_desing">

                    <!-- CABECERA  -->
                    <div>
                        <div class="form-header">
                            <a href="{{ route('perfil.show', Auth::user()) }}">
                                <iconify-icon class="icon-back" icon="solar:round-alt-arrow-left-bold"></iconify-icon>
                            </a>
                            <!-- FOTO DE PERFIL -->
                            <img class="bg-header" src="{{ asset('images/form-bg.jpg') }}" alt="">
                            <img class="perfil-user" src="{{ asset('images/user3.png') }}" alt="">
                            <img class="logo-arrax" src="{{ asset('images/logo6.png') }}" alt="">
                            <!-- <span class="text_title">Titulo Formulario</span> -->
                        </div>
                    </div>


                    <!-- CUERPO FORM  -->
                    <div class="form-body">
                        <!-- RUTA DE ACCION FORM-->
                        <form class="form_user_edit" method="POST" action="{{ route('perfil.update', Auth::user()) }}">

                            <!-- METODO DE SEGURIDAD Y ENVIO -->
                            @csrf
                            @method('PUT')


                            <!-- DATOS PERSONALES -->
                            <label for="name" class="text_subtitle sub_text mb-3">Datos Personales</label>
                            <div class="user_content mb-3">

                                <div class="user_item">
                                    <label for="name">Nombre Completo</label>
                                    <input id="name" type="text" class="form-control" name="name"
                                        value="{{ old('name', Auth::user()->name) }}">
                                </div>

                                <div class="user_item">
                                    <label for="correo">Correo Personal</label>
                                    <input id="correo" type="email" class="form-control" name="correo"
                                        value="{{ old('correo', Auth::user()->correo) ?: 'Sin dato' }}">
                                </div>

                            </div>

                            <div class="user_content mb-3">

                                <div class="user_item">
                                    <label for="telefono">Teléfono</label>
                                    <input id="telefono" type="text" class="form-control" name="telefono"
                                        value="{{ old('telefono', Auth::user()->telefono) ?: 'Sin dato' }}">
                                    <span class="form-indicacion">10 digitos</span>
                                </div>

                                <div class="user_item">
                                    <label for="profesion">Profesión</label>
                                    <input id="profesion" type="text" class="form-control" name="profesion"
                                        value="{{ old('profesion', Auth::user()->profesion) ?: 'Sin dato' }}">
                                </div>

                            </div>
                            @role('Administrador')
                                <!-- DATOS LABORALES -->
                                <label for="name" class="text_subtitle sub_text mb-3">Datos Laborales</label>
                                <div class="user_content mb-3">

                                    <div class="user_item">
                                        <label for="empleado">Número de empleado</label>
                                        <input id="empleado" type="text" class="form-control" name="empleado"
                                            value="{{ old('empleado', Auth::user()->empleado) ?: 'Sin dato' }}">
                                    </div>

                                    <div class="user_item">
                                        <label for="email">Correo Institucional</label>
                                        <input id="email" type="email" class="form-control" name="email"
                                            value="{{ old('email', Auth::user()->email) }}">
                                    </div>

                                </div>

                                <!--DEPTO DE USUARIO -->
                                <div class="user_content mb-3 justify-content-start" style="margin-left: 30px;">
                                    <div class="user_item select_dept">
                                        <label for="departamento_id" class="form-label">Departamento *</label>
                                        <select class="form-control" name="departamento_id" id="departamento_id"
                                            style="width:45%">
                                            <option value="" disabled selected>Seleccionar...</option>

                                            @foreach ($departamentos as $departamento)
                                                <option value="{{ $departamento->id }}"
                                                    {{ old('departamento_id', Auth::user()->departamento_id) == $departamento->id ? 'selecciona' : '' }}>
                                                    {{ $departamento->name }}
                                                </option>
                                            @endforeach

                                        </select>

                                        <!-- Mostrar mensaje de error de validación si existe -->
                                        @error('departamento_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror

                                        <span class="form-indicacion">Selecciona un departamento o area</span>
                                    </div>
                                </div>


                                <!-- CONTRASEÑA Y CONFIRMAR -->
                                <div class="user_content mb-3">

                                    <div class="user_item">
                                        <label for="password">Contraseña</label>
                                        <input id="password" type="password" class="form-control" name="password">
                                        <span class="form-indicacion">Mínimo 8 caracteries, letras y números</span>
                                    </div>

                                    <div class="user_item">
                                        <label for="password-confirm">Confirmar Contraseña</label>
                                        <input id="password-confirm" type="password" class="form-control"
                                            name="password_confirmation">
                                    </div>

                                </div>

                                <!-- ESTATUS DE USUARIO -->
                                <div class="form-group">
                                    <label for="name" class="text_subtitle sub_text">Estatus de usuario *</label>
                                    <br>
                                    <span class="form-indicacion" style="font-size: 10px">Se debe seleccionar un estado</span>
                                    <div class="permissions-box mt-4 mb-4">

                                        <!-- Mostrar Status -->
                                        <div class="status_user d-flex justify-content-evenly">
                                            <label>
                                                <input type="radio" name="status" value="Activo"
                                                    @checked(Auth::user()->status === 'Activo')>
                                                Activo
                                            </label>

                                            <label>
                                                <input type="radio" name="status" value="Inactivo"
                                                    @checked(Auth::user()->status === 'Inactivo')>
                                                Inactivo
                                            </label>
                                            <label>
                                                <input type="radio" name="status" value="Baja"
                                                    @checked(Auth::user()->status === 'Baja')>
                                                Baja
                                            </label>
                                            <div>
                                            </div>

                                        </div>
                                    </div>

                                    <!-- Sección de Permisos -->
                                    <div class="form-group">
                                        <label for="name" class="text_subtitle sub_text">Rol de usuario *</label>
                                        <br>
                                        <span class="form-indicacion" style="font-size: 10px">Se debe seleccionar un
                                            rol</span>
                                        <div class="permissions-box mt-4 mb-4">

                                            <!-- Mostrar Permisos -->
                                            <div>
                                                <ul class="checkbox-grid">
                                                    @foreach ($roles as $role)
                                                        <li>
                                                            <input type="checkbox" name="roles[]"
                                                                value="{{ $role->id }}" @checked(in_array($role->id, old('roles', Auth::user()->roles->pluck('id')->toArray())))>
                                                            <!-- @ checked recupera los check previo seleccionados despues de un error  -->
                                                            <span>{{ $role->name }}</span>
                                                            <!-- Nombre del permiso  -->
                                                        </li>
                                                    @endforeach
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                @endrole
                                <!-- BOTONES -->
                                <div class="form-bottons">
                                    <button type="submit" class="btn btn-primary">Actualizar</button>
                                    <a href="{{ route('perfil.show', Auth::user()->id) }}" class="btn btn-warning ">
                                        Cancelar</a>
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
