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
                            <a href="{{ route('admin.roles.index') }}">
                                <iconify-icon class="icon-back" icon="solar:round-alt-arrow-left-bold"></iconify-icon>
                            </a>
                            <!-- FOTO DE PERFIL -->
                            <img class="bg-header" src="{{ asset('images/form-bg.jpg') }}" alt="">
                            <img class="logo-arrax" src="{{ asset('images/logo6.png') }}" alt="">
                            <span class="text_title">Editar rol de usuario</span>
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
                        <!-- FORMULARIO -->
                        <form method="POST" action="{{ route('admin.roles.update', $role) }}">

                            @csrf
                            @method('PUT')
                            <!-- Input de Nombre -->
                            <div class="form-group">
                                <label for="name" class="text_content">Name</label>
                                <div class="">
                                    <input id="name" type="text" class="input-form" name="name"
                                        value="{{ old('name', $role->name) }}">
                                </div>
                            </div>

                            <!-- Sección de Permisos -->
                            <div class="form-group">
                                <label for="name" class="text_subtitle sub_text">Permisos</label>
                                <div class="permissions-box mt-4 mb-4">
                                    <input type="checkbox" id="seleccionar-todos">
                                    <label for="selectAll" class="checkbox-label mb-4">Todos</label>

                                    <!-- Mostrar Permisos -->
                                    <div>
                                        <ul class="checkbox-grid">
                                            @foreach ($permissions as $permission)
                                                <li>
                                                    <input class="opcion" type="checkbox" name="permissions[]"
                                                        value="{{ $permission->id }}" @checked(in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray())))>
                                                    <!-- @ checked -> recupera los check previo seleccionados despues de un error
                                                                                pluck -> por medio de un array recupera el ID de los permisos del rol-->
                                                    <span>{{ $permission->name }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Botones -->
                            <div class="form-bottons">
                                <button type="submit" class="btn btn-primary ">Actualizar</button>
                                <a href="{{ route('admin.roles.index') }}" class="btn btn-warning"> Cancelar</a>
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
