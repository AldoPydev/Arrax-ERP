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
                            <img class="perfil-user" src="{{ asset('images/user3.png') }}" alt="">
                            <img class="logo-arrax" src="{{ asset('images/logo6.png') }}" alt="">
                        </div>
                    </div>

                    <!-- CUERPO FORM  -->
                    <div class="form-body">
                        <form action="">

                            <div class="user_datos">

                                <!-- DATOS PERSONALES  -->
                                <div class="datos_personales">
                                    <h1 class="text_title text_title_user">{{ $user->name }}</h1>
                                    <div class="dato">
                                        <iconify-icon class="icon_user" icon="solar:user-rounded-bold"></iconify-icon>
                                        <span class="text_subtitle">{{ $user->profesion ?: 'Sin dato' }}</span>
                                    </div>
                                    <div class="dato">
                                        <iconify-icon class="icon_user" icon="solar:phone-bold"></iconify-icon>
                                        <span class="text_subtitle">{{ $user->telefono ?: 'Sin dato' }}</span>
                                        <p></p>
                                    </div>
                                    <div class="dato">
                                        <iconify-icon class="icon_user" icon="solar:letter-bold"></iconify-icon>
                                        <span class="text_subtitle">{{ $user->correo ?: 'Sin dato' }}</span>
                                    </div>

                                    <!-- BOTÓN EDITAR -->
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                        class="btn btn-primary mt-sm-3">Editar</a>
                                </div>


                                <!-- DATOS PERSONALES  -->
                                <div class="datos_laborales">
                                    <h1 class="text_title text_title_user">Datos Laborales</h1>
                                    <div class="dato">
                                        <iconify-icon class="icon_user" icon="solar:calendar-bold"></iconify-icon>
                                        <span class="text_subtitle">Fecha de ingreso: </span>
                                        <span
                                            class="text_content">{{ $user->created_at->format('Y-m-d') ?: 'Sin dato' }}</span>
                                    </div>
                                    <div class="dato">
                                        <iconify-icon class="icon_user" icon="solar:user-rounded-bold"></iconify-icon>
                                        <span class="text_subtitle">N. Empleado: </span>
                                        <span class="text_content">{{ $user->empleado ?: 'Sin dato' }}</span>
                                    </div>
                                    <div class="dato">
                                        <iconify-icon class="icon_user" icon="solar:buildings-bold"></iconify-icon>
                                        <span class="text_subtitle">Departamento: </span>
                                        <span class="text_content">Tics</span>
                                    </div>
                                    <div class="dato">
                                        <iconify-icon class="icon_user" icon="solar:letter-bold"></iconify-icon>
                                        <span class="text_subtitle">Correo: </span>
                                        <span class="text_content">{{ $user->email ?: 'Sin dato' }}</span>
                                    </div>
                                    <div class="dato">
                                        <iconify-icon class="icon_user" icon="solar:key-minimalistic-bold"></iconify-icon>
                                        <span class="text_subtitle">Rol de usuario: </span>

                                        <!-- MOSTRAR ROL DE USUARIO / DE NO EXISTIR MOSTRAR "Sin rol"  -->
                                        <span class="text_content">{{ $user->getRoleNames()->implode(', ') ?: 'Sin rol' }}
                                        </span>

                                    </div>
                                </div>


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
