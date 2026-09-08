<!--------- INDEX ROL TEMPLATE ------------>

@extends ("layouts.main")

<!-- DEFINIENDO CONTENIDO DE SECCION -->
@section('contenido')
    <div class="user_container">
        <div class="row">
            <div class="col">
                <!-- MENSAJES DE ACCIONES CREADO -->
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif

                <h2 class="text_title">Roles de usuarios</h2>
                <div class="mt-3 d-flex g-3">

                    <!-- BOTON DE CREAR NUEVO ROL -->
                    <button type="button" class="btn btn-primary">
                        <a href="{{ route('admin.roles.create') }}">Nuevo Rol</a>
                    </button>

                    <a class="btn btn-primary btn_back" href="{{ route('admin.users.index') }}">
                        <i class="bi bi-arrow-bar-left"></i>
                    </a>

                </div>
                <hr>

                <!-- TABLA ROLES -->
                <table class="table  table-hover align-middle mt-3">
                    <thead class="table-dark">
                        <tr>
                            <!-- HEADER DE TABLA -->
                            <td scope="col" class="text-center" style="width: 10%;">ID</td>
                            <td scope="col" class="text-start">Nombre</td>
                            <td scope="col" class="text-center" style="width: 20%;">Acciones</td>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- RECORRER LOS ROLES Y MOSTRAR -->
                        @foreach ($roles as $role)
                            <tr>
                                <td class="text-center">{{ $role->id }}</td>
                                <td class="text-start">{{ $role->name }}</td>
                                <td class="text-center">
                                    <!-- ACCIONES VER / EDITAR / ELIMINAR -->
                                    <div class="acciones-tabla" class="d-flex justify-content-center gap-2">

                                        <!-- VER USUARIO -->
                                        <a href="{{ route('admin.roles.show', $role->id) }}">
                                            <iconify-icon class="accion_icon" icon="solar:eye-linear"></iconify-icon>
                                        </a>

                                        <!-- EDITAR USUARIO -->
                                        <a href="{{ route('admin.roles.edit', $role->id) }}">
                                            <iconify-icon class="accion_icon" icon="solar:pen-linear"></iconify-icon>
                                        </a>

                                        <!-- MODAL CON ID DEL ROL -->
                                        <a href="#" data-bs-toggle="modal"
                                            data-bs-target="#modalEliminar{{ $role }}">
                                            <iconify-icon class="accion_icon accion_delete"
                                                icon="solar:trash-bin-2-linear"></iconify-icon>
                                        </a>

                                        <!-- MODAL DE CONFORMACIÓN -->
                                        <div class="modal fade" id="modalEliminar{{ $role }}" tabindex="-1"
                                            aria-labelledby="modalEliminarLabel{{ $role }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">

                                                    <!-- HEADER MODAL -->
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalEliminarLabel{{ $role }}">
                                                            Confirmar Eliminación</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                            aria-label="Close"></button>
                                                    </div>

                                                    <!-- MODAL -->
                                                    <div class="modal-body text-start">
                                                        ¿Seguro que deseas eliminar este elemento? Esta acción no se
                                                        puede
                                                        deshacer.
                                                    </div>

                                                    <div class="modal-footer">
                                                        <!-- CANCELAR MODAL -->
                                                        <button type="button" class="btn btn-secondary"
                                                            data-bs-dismiss="modal">Cancelar</button>

                                                        <!-- ELIMIANR ROL -->
                                                        <form action="{{ route('admin.roles.destroy', $role) }}"
                                                            method="post" class="d-inline">
                                                            @csrf
                                                            @method('delete')
                                                            <button type="submit" class="btn btn-warning">Eliminar</button>
                                                        </form>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Paginación -->
            {{ $roles->links() }}
        </div>
    </div>
@endsection
