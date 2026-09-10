# Requirements Document

## Introduction

Este documento especifica los requisitos del sistema de roles y permisos para Arrax ERP, un sistema de gestión institucional para escuelas y universidades. El sistema utiliza `spatie/laravel-permission` sobre Laravel 11 para controlar el acceso granular a los 19 módulos de la plataforma. Los permisos se definen con el formato `modulo.accion` y se asignan a roles predefinidos. Solo el administrador puede gestionar usuarios, roles y permisos. El dashboard oculta dinámicamente las tarjetas de módulos para los que el usuario autenticado no tiene permiso de tipo `.ver`.

## Glossary

- **Sistema**: La aplicación Arrax ERP basada en Laravel 11
- **Spatie**: Librería `spatie/laravel-permission` instalada y migrada en el proyecto
- **Rol**: Agrupación de permisos asignada a un usuario (admin, colaborador, practicante, invitado)
- **Permiso**: Capacidad atómica con formato `modulo.accion` (ej. `usuarios.ver`, `proyectos.crear`)
- **Módulo**: Cada una de las 19 secciones funcionales del ERP (avisos, biblioteca, calendario, capital-humano, citas, contactos, coordinaciones, correos, documentos, eventos, gerencia, mantenimiento, movilidad, proyectos, seguridad, servicios-escolares, tics, titulacion, usuarios)
- **Acción**: Operación que un rol puede ejecutar sobre un módulo: `crear`, `ver`, `editar`, `eliminar`
- **Admin**: Rol con acceso completo a todos los módulos y a la gestión de usuarios, roles y permisos
- **Colaborador**: Rol con acceso a módulos asignados con acciones `crear`, `ver` y `editar`
- **Practicante**: Rol con acceso a módulos asignados con acciones `crear` y `ver`
- **Invitado**: Rol con acceso a módulos asignados con acción `ver` únicamente
- **Dashboard**: Vista principal del ERP que muestra las tarjetas de acceso a módulos
- **Middleware**: Mecanismo de Laravel para interceptar peticiones HTTP y verificar permisos antes de ejecutar controladores
- **Seeder**: Clase de Laravel que inserta datos iniciales en la base de datos
- **Gate/Policy**: Mecanismos de autorización de Laravel para encapsular lógica de acceso

---

## Requirements

### Requirement 1: Estructura de Permisos Granulares por Módulo

**User Story:** Como administrador del sistema, quiero que los permisos estén definidos con el formato `modulo.accion` para cada uno de los 19 módulos, de forma que pueda controlar el acceso con granularidad por módulo y por tipo de operación.

#### Acceptance Criteria

1. THE Sistema SHALL definir 76 permisos individuales con el formato `modulo.accion`, donde `modulo` corresponde a uno de los 19 módulos (avisos, biblioteca, calendario, capital-humano, citas, contactos, coordinaciones, correos, documentos, eventos, gerencia, mantenimiento, movilidad, proyectos, seguridad, servicios-escolares, tics, titulacion, usuarios) y `accion` corresponde a uno de los 4 valores (crear, ver, editar, eliminar).

2. THE Sistema SHALL persistir todos los permisos en la tabla `permissions` gestionada por Spatie mediante un Seeder ejecutable con `php artisan db:seed`.

3. WHEN un permiso con el mismo nombre ya existe en la base de datos, THE Sistema SHALL omitir su creación sin lanzar una excepción, utilizando `firstOrCreate`.

4. THE Sistema SHALL registrar los permisos bajo el guard `web` de Laravel, que es el guard predeterminado de la aplicación.

---

### Requirement 2: Definición y Seeding de Roles

**User Story:** Como administrador del sistema, quiero que los cuatro roles (admin, colaborador, practicante, invitado) estén predefinidos con sus permisos correspondientes, de forma que al ejecutar los seeders el sistema quede listo para operar con la matriz de permisos correcta.

#### Acceptance Criteria

1. THE Sistema SHALL crear los roles `admin`, `colaborador`, `practicante` e `invitado` en la tabla `roles` gestionada por Spatie mediante un Seeder.

2. WHEN el Seeder de roles se ejecuta, THE Sistema SHALL asignar al rol `admin` los 76 permisos de todos los módulos y acciones, más los permisos de gestión de acceso (`usuarios.ver`, `usuarios.crear`, `usuarios.editar`, `usuarios.eliminar`, `roles.ver`, `roles.crear`, `roles.editar`, `roles.eliminar`).

3. WHEN el Seeder de roles se ejecuta, THE Sistema SHALL asignar al rol `colaborador` únicamente los permisos con acción `crear`, `ver` y `editar` para todos los módulos, excluyendo cualquier permiso con acción `eliminar`.

4. WHEN el Seeder de roles se ejecuta, THE Sistema SHALL asignar al rol `practicante` únicamente los permisos con acción `crear` y `ver` para todos los módulos.

5. WHEN el Seeder de roles se ejecuta, THE Sistema SHALL asignar al rol `invitado` únicamente los permisos con acción `ver` para todos los módulos.

6. WHEN un rol ya existe en la base de datos, THE Sistema SHALL actualizar sus permisos mediante `syncPermissions` sin duplicar el registro del rol.

---

### Requirement 3: Protección de Rutas mediante Middleware

**User Story:** Como administrador, quiero que las rutas de cada módulo estén protegidas por middleware de permisos, de forma que un usuario sin el permiso requerido reciba una respuesta de acceso denegado en lugar de ver el contenido.

#### Acceptance Criteria

1. WHEN un usuario autenticado intenta acceder a una ruta de módulo sin el permiso `modulo.ver` correspondiente, THE Sistema SHALL retornar una respuesta HTTP 403.

2. WHEN un usuario autenticado intenta ejecutar una acción de creación en un módulo sin el permiso `modulo.crear`, THE Sistema SHALL retornar una respuesta HTTP 403.

3. WHEN un usuario autenticado intenta ejecutar una acción de edición en un módulo sin el permiso `modulo.editar`, THE Sistema SHALL retornar una respuesta HTTP 403.

4. WHEN un usuario autenticado intenta ejecutar una acción de eliminación en un módulo sin el permiso `modulo.eliminar`, THE Sistema SHALL retornar una respuesta HTTP 403.

5. WHEN un usuario no autenticado intenta acceder a cualquier ruta protegida, THE Sistema SHALL redirigir al usuario a la ruta de login.

6. THE Sistema SHALL aplicar la verificación de permisos usando el middleware `permission` de Spatie en los grupos de rutas correspondientes a cada módulo.

---

### Requirement 4: Dashboard con Visibilidad Dinámica de Tarjetas

**User Story:** Como usuario autenticado, quiero que el dashboard solo muestre las tarjetas de los módulos para los que tengo permiso `.ver`, de forma que la interfaz refleje únicamente las funcionalidades a las que tengo acceso.

#### Acceptance Criteria

1. WHEN un usuario autenticado accede al dashboard, THE Sistema SHALL mostrar únicamente las tarjetas de los módulos para los cuales el usuario tiene el permiso `modulo.ver`.

2. WHEN un usuario autenticado no tiene el permiso `modulo.ver` para un módulo determinado, THE Sistema SHALL omitir la tarjeta de ese módulo en el dashboard sin mostrar un mensaje de error.

3. THE Sistema SHALL evaluar la visibilidad de cada tarjeta en la vista Blade utilizando la directiva `@can('modulo.ver')` de Laravel, sin requerir lógica adicional en el controlador del dashboard.

4. WHEN el rol `admin` accede al dashboard, THE Sistema SHALL mostrar las 19 tarjetas de módulos.

5. WHEN un usuario con rol `invitado` accede al dashboard, THE Sistema SHALL mostrar únicamente las tarjetas de los módulos para los que tenga asignado el permiso `modulo.ver`.

---

### Requirement 5: Gestión de Usuarios (exclusiva del Administrador)

**User Story:** Como administrador, quiero poder crear, editar, ver y eliminar usuarios desde el panel de administración, asignándoles uno o más roles, de forma que pueda gestionar el acceso de todos los empleados al ERP.

#### Acceptance Criteria

1. WHEN un usuario con rol `admin` accede a la sección de usuarios, THE Sistema SHALL mostrar el listado paginado de usuarios con su rol asignado.

2. WHEN un usuario sin el permiso `usuarios.ver` intenta acceder a la ruta de gestión de usuarios, THE Sistema SHALL retornar una respuesta HTTP 403.

3. WHEN el administrador crea un nuevo usuario, THE Sistema SHALL requerir los campos: nombre, correo personal, teléfono (10 dígitos), profesión, número de empleado (único), correo institucional (único) y contraseña (mínimo 8 caracteres con confirmación).

4. WHEN el administrador crea un nuevo usuario, THE Sistema SHALL asignar al menos un rol al usuario antes de persistir el registro.

5. WHEN el administrador actualiza un usuario existente, THE Sistema SHALL sincronizar el rol del usuario usando `sync` para reemplazar el rol anterior con el nuevo seleccionado.

6. WHEN el administrador elimina un usuario, THE Sistema SHALL eliminar el registro del usuario y sus relaciones de roles y permisos asociadas.

7. IF el campo contraseña está vacío durante la actualización de un usuario, THEN THE Sistema SHALL conservar la contraseña existente del usuario sin modificarla.

---

### Requirement 6: Gestión de Roles (exclusiva del Administrador)

**User Story:** Como administrador, quiero poder crear, editar y eliminar roles desde el panel de administración asignándoles permisos específicos, de forma que pueda adaptar el control de acceso a las necesidades de la institución.

#### Acceptance Criteria

1. WHEN un usuario con rol `admin` accede a la sección de roles, THE Sistema SHALL mostrar el listado paginado de roles con los permisos asignados a cada uno.

2. WHEN un usuario sin el permiso `roles.ver` intenta acceder a la ruta de gestión de roles, THE Sistema SHALL retornar una respuesta HTTP 403.

3. WHEN el administrador crea un nuevo rol, THE Sistema SHALL requerir un nombre único en la tabla `roles` y al menos un permiso seleccionado del listado disponible.

4. WHEN el administrador actualiza un rol, THE Sistema SHALL sincronizar los permisos del rol usando `syncPermissions` para reemplazar los permisos anteriores con los nuevos seleccionados.

5. WHEN el administrador elimina un rol, THE Sistema SHALL eliminar el registro del rol y desvincular todos los usuarios que tenían ese rol asignado.

6. IF el nombre del rol ya existe en la tabla `roles` durante la creación, THEN THE Sistema SHALL retornar un error de validación al administrador indicando que el nombre del rol ya está en uso.

---

### Requirement 7: Compatibilidad y Migración del Sistema Existente

**User Story:** Como desarrollador del proyecto, quiero que el nuevo sistema de permisos reemplace la estructura actual (permisos tipo `acceso usuarios`) sin romper la funcionalidad existente, de forma que la migración sea limpia y sin regresiones.

#### Acceptance Criteria

1. WHEN se ejecutan los nuevos Seeders, THE Sistema SHALL limpiar los permisos con formato antiguo (`acceso usuarios`, `acceso gerencia`, etc.) antes de insertar los permisos con formato `modulo.accion`, utilizando `Permission::truncate()` y `Role::truncate()` de forma segura previo al seeding.

2. THE Sistema SHALL actualizar el `PermissionSeeder` y el `RoleSeeder` existentes para usar la nueva estructura de permisos con formato `modulo.accion`, reemplazando completamente el contenido actual.

3. THE Sistema SHALL actualizar el `DatabaseSeeder` para ejecutar `PermissionSeeder` antes de `RoleSeeder` garantizando el orden correcto de dependencia.

4. WHEN se completa la migración, THE Sistema SHALL mantener los cuatro roles base (admin, colaborador, practicante, invitado) con sus nuevos permisos asignados según la matriz definida en el Requirement 2.
