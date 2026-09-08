@extends ("layouts.main")

<!-- DEFINIENDO CONTENIDO DE SECCION -->
@section('contenido')
    <div class="user_container">
        <div class="row">
            <div class="col">

                <!-- TITULO DE SECCION -->
                <h2 class="text_title">Lista de usuarios</h2>
                <div class="search_box mt-3">

                    <!-- BOTON DE CREAR NUEVO USUARIO -->
                    <div class="boton_header">
                        <button type="button" class="btn btn-primary">
                            <a href="{{ route('admin.users.create') }}">Nuevo usuario</a>
                        </button>

                        <!-- BOTON ROLES -->
                        <a class="btn btn-secondary  mt-3 mb-3" href="{{ route('admin.roles.index') }}">
                            <iconify-icon class="btn_reverse_icon" icon="solar:lock-unlocked-outline"></iconify-icon>
                            <span>Roles de usuario</span>
                        </a>

                    </div>


                    <!-- BUSCADOR -->
                    <form class="d-flex" role="search">
                        <input class="buscador_box form-control me-2" type="search" placeholder="Buscar..."
                            aria-label="Search" />
                        <button class="btn btn-primary" type="submit">Buscar</button>
                    </form>
                </div>



                <br>

                <!-- TABLA USUARIOS -->
                <table class="table  table-hover align-middle mt-3">
                    <thead class="table-dark">
                        <tr>
                            <!-- HEADER DE TABLA -->
                            <th scope="col" class="text-center" style="width: 10%">N. Empleado</th>
                            <th scope="col" class="text-start">Nombre</th>
                            <th scope="col" class="text-start">Correo</th>
                            <th scope="col" class="text-start">Departamento</th>
                            <th scope="col" class="text-start" style="width: 10%">Rol</th>
                            <th scope="col" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- RECORRER LOS USUARIOS Y MOSTRAR -->
                        @foreach ($users as $user)
                            <tr>
                                <td class="text-center">{{ $user->empleado }}</td>
                                <td class="text-start">{{ $user->name }}</td>
                                <td class="text-start">{{ $user->email }}</td>
                                <td class="text-start">Mantenimiento</td>

                                <!-- MOSTRAR ROL DE USUARIO -->
                                <td class="text-start">
                                    {{ $user->getRoleNames()->implode(', ') ?: 'Sin rol' }}
                                </td>


                                <td class="text-center">
                                    <!-- ACCIONES VER / EDITAR / ELIMINAR -->
                                    <div class="acciones-tabla">

                                        <!-- VER USUARIO -->
                                        <a href="{{ route('admin.users.show', $user) }}">
                                            <iconify-icon class="accion_icon" icon="solar:eye-linear"></iconify-icon>
                                        </a>

                                        <!-- EDITAR USUARIO -->
                                        <a href="{{ route('admin.users.edit', $user) }}">
                                            <iconify-icon class="accion_icon" icon="solar:pen-linear"></iconify-icon>
                                        </a>

                                        <!-- MODAL CON ID DEL UCUARIO -->
                                        <a href="#" data-bs-toggle="modal"
                                            data-bs-target="#modalEliminar{{ $user->id }}">
                                            <iconify-icon class="accion_icon accion_delete"
                                                icon="solar:trash-bin-2-linear"></iconify-icon>
                                        </a>

                                        <!-- MODAL DE CONFORMACIÓN -->
                                        <div class="modal fade" id="modalEliminar{{ $user->id }}" tabindex="-1"
                                            aria-labelledby="modalEliminarLabel{{ $user->id }}" aria-hidden="true">
                                            <div class="modal-dialog modal-dialog-centered">
                                                <div class="modal-content">

                                                    <!-- HEADER MODAL -->
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="modalEliminarLabel{{ $user->id }}">
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

                                                        <!-- ELIMIANR USUARIO -->
                                                        <form action="{{ route('admin.users.destroy', $user->id) }}"
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
            {{ $users->links() }}
        </div>
    </div>
@endsection
