@extends('layouts.main')
@section('contenido')
    <div class="container">
        <div class="row">
            <div class="col">
                <!-- CARD SHOW USER  -->
                <div class="cars_show_user">
                    <div class="card_body">
                        <!-- CABECERA  -->
                        <div>
                            <div class="card_show_header">
                                <!-- ICONO DE REGRESAR-->
                                <a href="{{ route('admin.users.index') }}">
                                    <iconify-icon class="icon-back" icon="solar:round-alt-arrow-left-bold"></iconify-icon>
                                </a>

                                <!-- IMAGEN DE CABECERA -->
                                <img class="bg-header" src="{{ asset('images/form-bg.jpg') }}" alt="">

                                <!-- MOSTRAR LOGO -->
                                <img class="logo-arrax" src="{{ asset('images/logo6.png') }}" alt="">
                            </div>
                        </div>

                        <!-- FOTO PERFIL  -->
                        <div class="perfil-user">
                            <img class="foto-user"
                                src="{{ $user->image_path ? Storage::url($user->image_path) : asset('images/user.png') }}"
                                alt="" id="imgPreview">
                        </div>

                        <div class="show_container_user">
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


                            <!-- DATOS LABORALES  -->
                            <div class="datos_laborales">
                                <h1 class="text_title text_title_user">Datos Laborales</h1>
                                <div class="dato">
                                    <iconify-icon class="icon_user" icon="solar:calendar-bold"></iconify-icon>
                                    <span class="text_subtitle">Fecha de ingreso: </span>
                                    <span
                                        class="text_content">{{ $user->created_at?->format('Y-m-d') ?: 'Sin dato' }}</span>
                                </div>
                                <div class="dato">
                                    <iconify-icon class="icon_user" icon="solar:user-rounded-bold"></iconify-icon>
                                    <span class="text_subtitle">N. Empleado: </span>
                                    <span class="text_content">{{ $user->empleado ?: 'Sin dato' }}</span>
                                </div>
                                <div class="dato">
                                    <iconify-icon class="icon_user" icon="solar:buildings-bold"></iconify-icon>
                                    <span class="text_subtitle">Departamento: </span>


                                    <!-- MOSTRAR DEPTO RELACIOANDO AL USUARIO DE LA TABLA DEPTO -->
                                    <span class="text_content">{{ $user->departamento->name ?? 'Sin departamento' }}</span>

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
                                <div class="dato">
                                    <iconify-icon class="icon_user"
                                        icon="solar:settings-minimalistic-bold-duotone"></iconify-icon>
                                    <span class="text_subtitle">Estatus: </span>

                                    <!-- MOSTRAR STATUS DE USUARIO / DE NO EXISTIR MOSTRAR "Sin rol"  -->
                                    <span class="text_content">{{ $user->status ?: 'Sin dato' }}</span>
                                    </span>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
