@extends ("layouts.main")

<!-- DEFINIENDO CONTENIDO DE SECCION -->
@section('contenido')
    <main class="panel container" id="panel">
        <div class="card area-1">
            <a href="#">
                <img src="{{ asset('iconos-secciones/avisos.png') }}" alt="">
                <h2 class="title-card">AVISOS</h2>
            </a>
        </div>
        <div class="card area-1">
            <a href="#">
                <img src="{{ asset('iconos-secciones/biblioteca.png') }}" alt="">
                <h2 class="title-card">BIBLIOTECA</h2>
            </a>
        </div>
        <div class="card area-1">
            <a href="#">
                <img src="{{ asset('iconos-secciones/calendario.png') }}" alt="">
                <h2 class="title-card">CALENDARIO</h2>
            </a>
        </div>
        <div class="card area-1">
            <a href="#">
                <img src="{{ asset('iconos-secciones/RH.png') }}" alt="">
                <h2 class="title-card">CAPITAL HUMANO</h2>
            </a>
        </div>
        <div class="card area-1">
            <a href="#">
                <img src="{{ asset('iconos-secciones/citas.png') }}" alt="">
                <h2 class="title-card">CITAS</h2>
            </a>
        </div>
        <div class="card area-2">
            <a href="#">
                <img src="{{ asset('iconos-secciones/contactos.png') }}" alt="">
                <h2 class="title-card">CONTACTOS</h2>
            </a>
        </div>
        <div class="card area-2">
            <a href="#">
                <img src="{{ asset('iconos-secciones/coordinacion.png') }}" alt="">
                <h2 class="title-card">COORDINACIONES</h2>
            </a>
        </div>
        <div class="card area-2">
            <a href="#">
                <img src="{{ asset('iconos-secciones/correo.png') }}" alt="">
                <h2 class="title-card">CORREOS</h2>
            </a>
        </div>
        <div class="card area-2">
            <a href="#">
                <img src="{{ asset('iconos-secciones/documentos.png') }}" alt="">
                <h2 class="title-card">DOCUMENTOS</h2>
            </a>
        </div>
        <div class="card area-2">
            <a href="#">
                <img src="{{ asset('iconos-secciones/eventos.png') }}" alt="">
                <h2 class="title-card">EVENTOS</h2>
            </a>
        </div>
        <div class="card area-3">
            <a href="#">
                <img src="{{ asset('iconos-secciones/gerencia.png') }}" alt="">
                <h2 class="title-card">GERENCIA</h2>
            </a>
        </div>
        <div class="card area-3">
            <a href="#">
                <img src="{{ asset('iconos-secciones/mantenimiento.png') }}" alt="">
                <h2 class="title-card">MANTENIMIENTO</h2>
            </a>
        </div>
        <div class="card area-3">
            <a href="#">
                <img src="{{ asset('iconos-secciones/movilidad.png') }}" alt="">
                <h2 class="title-card">MOVILIDAD</h2>
            </a>
        </div>
        <div class="card area-3">
            <a href="#">
                <img src="{{ asset('iconos-secciones/proyectos.png') }}" alt="">
                <h2 class="title-card">PROYECTOS</h2>
            </a>
        </div>
        <div class="card area-3">
            <a href="#">
                <img src="{{ asset('iconos-secciones/seguridad.png') }}" alt="">
                <h2 class="title-card">SEGURIDAD</h2>
            </a>
        </div>
        <div class="card area-4">
            <a href="#">
                <img src="{{ asset('iconos-secciones/escolares.png') }}" alt="">
                <h2 class="title-card">SERVICIOS ESCOLARES</h2>
            </a>
        </div>
        <div class="card area-4">
            <a href="#">
                <img src="{{ asset('iconos-secciones/ti.png') }}" alt="">
                <h2 class="title-card">TICS</h2>
            </a>
        </div>
        <div class="card area-4">
            <a href="#">
                <img src="{{ asset('iconos-secciones/titulacion.png') }}" alt="">
                <h2 class="title-card">TITULACION</h2>
            </a>
        </div>
        <div class="card area-4">
            <a href="{{ route('admin.users.index') }}">
                <img src="{{ asset('iconos-secciones/personal.png') }}" alt="">
                <h2 class="title-card">USUARIOS</h2>
            </a>
        </div>
    </main>
@endsection
