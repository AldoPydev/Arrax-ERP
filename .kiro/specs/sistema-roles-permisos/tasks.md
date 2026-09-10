# Implementation Plan: Sistema de Roles y Permisos

## Overview

Plan de implementación incremental del sistema de roles y permisos para Arrax ERP usando `spatie/laravel-permission` sobre Laravel 11. Se reemplaza la estructura de permisos antigua (`acceso usuarios`) por el esquema `modulo.accion` con 76 permisos, 4 roles, rutas protegidas por middleware y un dashboard con visibilidad dinámica de tarjetas.

---

## Tasks

- [ ] 1. Refactorizar Seeders (PermissionSeeder y RoleSeeder)
  - [ ] 1.1 Reescribir `PermissionSeeder` con la nueva estructura `modulo.accion`
    - Añadir la constante `MODULOS` con los 19 módulos y `ACCIONES` con los 4 valores
    - Agregar `DB::statement('SET FOREIGN_KEY_CHECKS=0;')` (o `PRAGMA foreign_keys = OFF` para SQLite) antes de `Permission::truncate()` y restaurarlo después
    - Llamar a `app()[PermissionRegistrar::class]->forgetCachedPermissions()` al inicio
    - Usar `Permission::firstOrCreate(['name' => "$modulo.$accion", 'guard_name' => 'web'])` en el loop doble
    - _Requirements: 1.1, 1.2, 1.3, 1.4, 7.1, 7.2_

  - [ ]* 1.2 Escribir property test para idempotencia del PermissionSeeder
    - **Property 1: Idempotencia del PermissionSeeder**
    - Ejecutar `PermissionSeeder::run()` dos veces; afirmar que `Permission::count() === 76` y que ningún nombre sigue el patrón `acceso *`
    - **Validates: Requirements 1.1, 1.2, 1.3, 1.4**

  - [ ]* 1.3 Escribir property test para migración limpia de formato antiguo
    - **Property 13: Migración limpia — ausencia de permisos con formato antiguo**
    - Insertar permisos con formato `acceso X` en la BD de prueba; ejecutar `PermissionSeeder`; afirmar que ningún registro en `permissions` tiene el patrón `acceso *`
    - **Validates: Requirements 7.1, 7.2**

  - [ ] 1.4 Reescribir `RoleSeeder` con la matriz de permisos por rol
    - Agregar `DB::statement` para deshabilitar foreign keys antes de `Role::truncate()` y rehabilitarlos después
    - Implementar `permisosParaAcciones(array $acciones): array` que itera sobre los 19 módulos
    - Crear el mapa `admin → [crear,ver,editar,eliminar]`, `colaborador → [crear,ver,editar]`, `practicante → [crear,ver]`, `invitado → [ver]`
    - Usar `Role::firstOrCreate` + `syncPermissions` para cada rol
    - _Requirements: 2.1, 2.2, 2.3, 2.4, 2.5, 2.6_

  - [ ]* 1.5 Escribir property tests para restricciones de permisos por rol
    - **Property 2: Restricción de permisos del rol `colaborador`** — ningún permiso del rol `colaborador` debe tener acción `eliminar`
    - **Property 3: Restricción de permisos del rol `practicante`** — acciones solo `crear` o `ver`
    - **Property 4: Restricción de permisos del rol `invitado`** — acción solo `ver`
    - **Property 5: Idempotencia del RoleSeeder** — ejecutar dos veces produce exactamente 4 roles sin duplicados
    - **Validates: Requirements 2.3, 2.4, 2.5, 2.6**

  - [ ] 1.6 Actualizar `DatabaseSeeder` para garantizar orden `PermissionSeeder → RoleSeeder`
    - Reemplazar el contenido de `$this->call([...])` con el orden correcto
    - _Requirements: 7.3, 7.4_

- [ ] 2. Checkpoint — Validar Seeders
  - Ejecutar `php artisan db:seed` y verificar que se crean 76 permisos y 4 roles sin errores. Preguntar al usuario si tiene dudas.

- [ ] 3. Proteger Rutas con Middleware de Spatie
  - [ ] 3.1 Actualizar `routes/web.php` con grupos de middleware `auth` + `permission`
    - Envolver las rutas resource de `admin/users` con `middleware(['auth', 'permission:usuarios.ver'])`
    - Envolver las rutas resource de `admin/roles` con `middleware(['auth', 'permission:roles.ver'])`
    - Añadir comentarios con el patrón recomendado para rutas de módulos futuros (granular por acción)
    - Asegurar que la ruta del dashboard sigue protegida con `middleware('auth')`
    - _Requirements: 3.1, 3.2, 3.3, 3.4, 3.5, 3.6_

  - [ ]* 3.2 Escribir tests de middleware para control de acceso (HTTP 403)
    - **Property 6: Control de acceso por módulo y acción**
    - Crear usuario sin permisos e intentar GET a `admin/users` y `admin/roles`; afirmar respuesta 403
    - Crear usuario con `usuarios.ver` e intentar GET a `admin/users`; afirmar respuesta 200
    - **Validates: Requirements 3.1, 3.2, 3.3, 3.4**

  - [ ]* 3.3 Escribir tests de redirección a login para usuarios no autenticados
    - **Property 7: Redirección a login para usuarios no autenticados**
    - Hacer GET sin autenticar a `admin/users` y `admin/roles`; afirmar redirect 302 hacia `/login`
    - **Validates: Requirements 3.5**

- [ ] 4. Crear vista de error 403 con branding de Arrax ERP
  - [ ] 4.1 Crear `resources/views/errors/403.blade.php`
    - Extender `layouts.main`
    - Mostrar título "403", mensaje de acceso denegado y enlace "Volver al Dashboard" usando `route('dashboard.index')`
    - _Requirements: 3.1, 3.2, 3.3, 3.4_

- [ ] 5. Actualizar Dashboard con Visibilidad Dinámica de Tarjetas
  - [ ] 5.1 Actualizar `resources/views/dashboard/home.blade.php` con directivas `@can`
    - Envolver cada una de las 19 tarjetas de módulo con `@can('modulo.ver') ... @endcan`
    - La tarjeta de usuarios debe enlazar a `route('admin.users.index')` y usar `@can('usuarios.ver')`
    - No se requieren cambios en el controlador `Dashboard`
    - _Requirements: 4.1, 4.2, 4.3, 4.4, 4.5_

  - [ ]* 5.2 Escribir feature test para visibilidad dinámica del dashboard
    - **Property 8: Visibilidad de tarjetas del dashboard según permisos `.ver`**
    - Crear usuario con solo `avisos.ver` y `calendario.ver`; acceder al dashboard; afirmar que solo aparecen 2 tarjetas
    - Crear usuario admin; acceder al dashboard; afirmar que aparecen las 19 tarjetas
    - **Validates: Requirements 4.1, 4.2, 4.4, 4.5**

- [ ] 6. Checkpoint — Validar Dashboard y Middleware
  - Verificar con pruebas automatizadas que el dashboard oculta tarjetas y el middleware devuelve 403. Preguntar al usuario si tiene dudas.

- [ ] 7. Actualizar UserController
  - [ ] 7.1 Actualizar método `store` en `UserController`
    - Asegurar que la validación incluye: `name`, `correo`, `telefono` (digits:10), `profesion`, `empleado` (unique), `email` (unique), `password` (min:8, confirmed)
    - Cambiar la asignación de rol a `$user->syncRoles($request->input('role'))` tras `User::create($data)`
    - _Requirements: 5.3, 5.4_

  - [ ] 7.2 Actualizar método `update` en `UserController`
    - Mantener las validaciones con excepciones para el usuario actual (`unique:users,email,{id}`, `unique:users,empleado,{id}`)
    - Conservar la contraseña existente si el campo `password` está vacío (omitirlo del `fill` o usar `when`)
    - Aplicar `$user->syncRoles($request->input('role', []))` para sincronizar el rol
    - _Requirements: 5.5, 5.7_

  - [ ] 7.3 Actualizar método `destroy` en `UserController`
    - Verificar que `$user->delete()` es suficiente (Spatie limpia `model_has_roles` automáticamente)
    - No se requieren cambios adicionales, solo confirmar la lógica existente
    - _Requirements: 5.6_

  - [ ] 7.4 Actualizar vistas `create.blade.php` y `edit.blade.php` de usuarios
    - Agregar el `<select name="role">` con `@foreach($roles as $role)` y lógica `selected` para edición
    - Pasar `$roles = Role::all()` desde los métodos `create()` y `edit()` del controlador
    - _Requirements: 5.3, 5.4, 5.5_

  - [ ]* 7.5 Escribir property tests para sincronización de rol en usuario
    - **Property 9: Sincronización de rol en actualización de usuario** — después de update el usuario tiene solo el nuevo rol
    - **Property 10: Limpieza de relaciones al eliminar usuario** — eliminar usuario borra todos los registros en `model_has_roles`
    - **Validates: Requirements 5.5, 5.6**

- [ ] 8. Actualizar RolesController
  - [ ] 8.1 Actualizar método `store` en `RolesController`
    - Agregar validación: `name` (required, unique:roles), `permissions` (required|array), `permissions.*` (exists:permissions,id)
    - Cambiar `$role->permissions()->attach(...)` por `$role->syncPermissions(...)` para consistencia
    - _Requirements: 6.1, 6.3, 6.6_

  - [ ] 8.2 Actualizar método `update` en `RolesController`
    - Confirmar que usa `syncPermissions` con los IDs del request
    - Ajustar validación para excluir el rol actual en la regla unique (`unique:roles,name,{id}`)
    - _Requirements: 6.4_

  - [ ] 8.3 Verificar método `destroy` en `RolesController`
    - Confirmar que Spatie limpia `role_has_permissions` y `model_has_roles` al eliminar el rol
    - _Requirements: 6.5_

  - [ ] 8.4 Actualizar vistas `create.blade.php` y `edit.blade.php` de roles
    - Implementar el listado de checkboxes `name="permissions[]"` agrupados por módulo usando `$permisosPorModulo`
    - Pasar `$permisosPorModulo = Permission::all()->groupBy(fn($p) => explode('.', $p->name)[0])` desde el controlador
    - Para `edit.blade.php`, pasar también `$permisosDelRol = $role->permissions->pluck('id')->toArray()`
    - _Requirements: 6.1, 6.3, 6.4_

  - [ ]* 8.5 Escribir property tests para sincronización de permisos en rol
    - **Property 11: Sincronización de permisos en actualización de rol** — después de update el rol tiene solo el nuevo conjunto de permisos
    - **Property 12: Limpieza de relaciones al eliminar rol** — eliminar rol borra todos los registros en `model_has_roles` donde `role_id` corresponde al rol eliminado
    - **Property 14: Validación de unicidad del nombre de rol** — intentar crear rol con nombre duplicado retorna error de validación sin insertar duplicado
    - **Validates: Requirements 6.4, 6.5, 6.3, 6.6**

- [ ] 9. Checkpoint Final — Asegurar que todas las pruebas pasan
  - Ejecutar `php artisan test` y verificar que todos los tests pasan. Preguntar al usuario si tiene dudas antes de cerrar.

---

## Notes

- Las tareas marcadas con `*` son opcionales y pueden omitirse para un MVP más rápido
- El orden de los Seeders es una dependencia estricta: `PermissionSeeder` antes de `RoleSeeder`
- El middleware `permission` de Spatie requiere el alias registrado en `bootstrap/app.php` o `Kernel.php`; verificar que ya está configurado antes de la tarea 3
- Para SQLite (ambiente de pruebas), usar `PRAGMA foreign_keys = OFF/ON` en lugar de `SET FOREIGN_KEY_CHECKS`
- El controlador `Dashboard` no requiere cambios; toda la lógica de visibilidad es declarativa en la vista Blade
- Cada tarea referencia los requisitos específicos para trazabilidad

## Task Dependency Graph

```json
{
  "waves": [
    { "id": 0, "tasks": ["1.1", "1.4"] },
    { "id": 1, "tasks": ["1.2", "1.3", "1.5", "1.6"] },
    { "id": 2, "tasks": ["3.1", "4.1", "5.1"] },
    { "id": 3, "tasks": ["3.2", "3.3", "5.2", "7.1", "7.4", "8.1", "8.4"] },
    { "id": 4, "tasks": ["7.2", "7.3", "7.5", "8.2", "8.3", "8.5"] }
  ]
}
```
