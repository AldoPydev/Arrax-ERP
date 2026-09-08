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
                            <span class="text_title">Rol de usuario</span>
                        </div>
                    </div>

                    <!-- CUERPO FORM  -->
                    <div class="form-body">
                        <!-- FORMULARIO -->
                        <form>

                            <!-- Input de Nombre -->
                            <div class="form-group">
                                <label for="name" class="text_content">Name</label>
                                <div class="">
                                    <label for="name" class="text_title">{{ $role->name }}</label>
                                </div>
                            </div>

                            <!-- Sección de Permisos -->
                            <div class="form-group">
                                <label for="name" class="text_subtitle sub_text">Permisos asigandos</label>
                                <div class="permissions-box mt-4 mb-4">

                                    <!-- Mostrar Permisos -->
                                    <div>
                                        <ul class="checkbox-grid">
                                            @foreach ($permissions as $permission)
                                                <li>
                                                    <input type="checkbox" name="permissions[]"
                                                        value="{{ $permission->id }}" @checked(in_array($permission->id, old('permissions', $role->permissions->pluck('id')->toArray()))) disabled>
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
                                <a href="{{ route('admin.roles.edit', $role->id) }}"" class="btn btn-primary">Editar rol</a>
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
