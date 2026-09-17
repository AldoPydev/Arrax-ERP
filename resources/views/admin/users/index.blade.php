@extends ("layouts.main")

<!-- DEFINIENDO CONTENIDO DE SECCION -->
@section('contenido')
    <div class="user_container">
        <div class="row">
            <div class="col">

                <!-- TITULO DE SECCION -->
                <h2 class="text_title">Lista de usuarios</h2>
                <div class="search_box mt-3">

                    <!-- BOTON NUEVO USUARIO -->
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
                    <form class="d-flex gap-2" action="{{ route('admin.users.index') }}" method="GET" role="search"
                        name="buscar">
                        <input class="buscador_box form-control me-2" name ="buscar" type="search" placeholder="Buscar..."
                            value="{{ $_REQUEST['buscar'] ?? '' }}" aria-label="Search" id="input-buscar" />
                        <button class="btn btn-primary" type="submit">Buscar</button>

                        <!-- LIMPIAR BUSQUEDA -->
                        @if ($buscar)
                            <a class="btn btn-success align-content-center"
                                href="{{ route('admin.users.index') }}">Limpiar</a>
                        @endif
                    </form>
                </div>



                <br>

                <!-- TABLA USUARIOS -->
                <table class="table  table-hover align-middle mt-3" id="contenedor-tabla">
                    <thead class="table-dark">
                        <tr>
                            <!-- HEADER DE TABLA -->
                            <th scope="col" class="text-center" style="width: 7%">N. Empleado</th>
                            <th scope="col" class="text-center">Nombre</th>
                            <th scope="col" class="text-center">Correo</th>
                            <th scope="col" class="text-center">Departamento</th>
                            <th scope="col" class="text-center" style="width: 13%">Rol</th>
                            <th scope="col" class="text-center" style="width: 10%">Estado</th>
                            <th scope="col" class="text-center" style="width: 10%">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>


                        <!-- RECORRER LOS USUARIOS Y MOSTRAR -->
                        @forelse ($users as $user)
                            <tr>
                                <td class="text-center">{{ $user->empleado }}</td>
                                <td class="text-start">{{ $user->name }}</td>
                                <td class="text-start">{{ $user->email }}</td>
                                <td class="text-start">{{ $user->departamento->name ?? 'Sin departamento' }}</td>

                                <!-- MOSTRAR ROL DE USUARIO -->
                                <td class="text-start">
                                    {{ $user->getRoleNames()->implode(', ') ?: 'Sin rol' }}
                                </td>

                                <!-- MOSTRAR STATUS DEL USUARIO -->
                                @php
                                    // SE ASIGANA UN COLOR POR STATUS
                                    $statusColor = match (strtolower($user->status)) {
                                        'activo' => 'status-activo',
                                        'inactivo' => 'status-inactivo',
                                        'baja' => 'status-baja',
                                        default => 'text-secondary', // Color por defecto
                                    };
                                @endphp

                                <td class="text-start status {{ $statusColor }}">
                                    <i class="bi bi-circle-fill" style="font-size: 10px"></i>
                                    <span class="status">{{ $user->status }}</span>
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


                                        <!-- ELIMIANR USUARIO -->
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
                                                        ¿Seguro que deseas eliminar al usario? Esta acción no se
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

                            <!-- BUSQUEDA SIN RESULTADOS -->
                        @empty
                            <tr>
                                <td colspan="3">No se encontraron resultados.</td>
                            </tr>
                        @endforelse


                    </tbody>
                </table>
            </div>
            <!-- Paginación -->
            {{ $users->links() }}

        </div>
    </div>
@endsection
