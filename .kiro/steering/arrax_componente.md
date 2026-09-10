---
name: arrax_componentes
description: "componentes y estructuras de modelos."
inclusionMode: "always"
---

# Estructura de Formulario

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
                            <!-- <span class="text_title">Titulo Formulario</span> -->
                        </div>
                    </div>

                    <!-- CUERPO FORM  -->
                    <div class="form-body">
                        <form action="">

                            

                         
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

