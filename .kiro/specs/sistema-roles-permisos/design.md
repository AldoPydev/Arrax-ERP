# Design Document — Sistema de Roles y Permisos

## Overview

El sistema de roles y permisos de Arrax ERP implementa control de acceso granular usando `spatie/laravel-permission ^8.3` sobre Laravel 13/11. El diseño reemplaza la estructura de permisos plana actual (`acceso usuarios`, `acceso gerencia`, etc.) por un esquema `modulo.accion` de 76 permisos sobre 19 módulos × 4 acciones. Los cuatro roles predefinidos (admin, colaborador, practicante, invitado) consumen esos permisos. Las rutas se protegen con el middleware `permission` de Spatie y el dashboard filtra tarjetas dinámicamente con directivas `@can` de Blade.

---

## Architecture

```
┌─────────────────────────────────────────────────────────────┐
│                       HTTP Request                          │
└────────────────────────┬────────────────────────────────────┘
                         │
              ┌──────────▼──────────┐
              │   Middleware Stack  │
              │  auth (Laravel)     │
              │  permission (Spatie)│
              └──────────┬──────────┘
                         │ 403 si sin permiso
         ┌───────────────▼────────────────────┐
         │            Controllers             │
         │  Dashboard (index)                 │
         │  admin/UserController  (CRUD)      │
         │  admin/RolesController (CRUD)      │
         └──────────────┬─────────────────────┘
                        │
         ┌──────────────▼─────────────────────┐
         │              Models                │
         │  User (HasRoles via Spatie)         │
         │  Role, Permission (Spatie)          │
         └──────────────┬─────────────────────┘
                        │
         ┌──────────────▼─────────────────────┐
         │           Database                 │
         │  users, roles, permissions         │
         │  model_has_roles                   │
         │  model_has_permissions             │
         │  role_has_permissions              │
         └────────────────────────────────────┘
```

### Capas del sistema

| Capa | Responsabilidad |
|------|----------------|
| **Seeders** | Crear los 76 permisos `modulo.accion` y los 4 roles con sus permisos asignados. Limpian la estructura antigua antes de insertar. |
| **Middleware** | Interceptar peticiones; devolver 403 si falta permiso, redirigir a login si no hay sesión. |
| **Controllers** | Lógica CRUD de usuarios y roles; validaciones; asignación/sincronización de roles y permisos. |
| **Views (Blade)** | Dashboard con `@can` por tarjeta; formularios de creación y edición con selección de roles/permisos. |
| **Routes** | Agrupación por middleware `auth` + `permission`; resource routes para admin. |

---

## Components

### 1. PermissionSeeder (refactorizado)

**Archivo:** `database/seeders/PermissionSeeder.php`

Responsabilidades:
- Limpiar permisos existentes con `Permission::truncate()` dentro de una transacción.
- Generar y persistir los 76 permisos con `Permission::firstOrCreate(['name' => ..., 'guard_name' => 'web'])`.

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    // 19 módulos × 4 acciones = 76 permisos
    private const MODULOS = [
        'avisos', 'biblioteca', 'calendario', 'capital-humano', 'citas',
        'contactos', 'coordinaciones', 'correos', 'documentos', 'eventos',
        'gerencia', 'mantenimiento', 'movilidad', 'proyectos', 'seguridad',
        'servicios-escolares', 'tics', 'titulacion', 'usuarios',
    ];

    private const ACCIONES = ['crear', 'ver', 'editar', 'eliminar'];

    public function run(): void
    {
        // Limpiar estructura antigua (desactiva foreign keys temporalmente)
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Permission::truncate();

        foreach (self::MODULOS as $modulo) {
            foreach (self::ACCIONES as $accion) {
                Permission::firstOrCreate([
                    'name'       => "{$modulo}.{$accion}",
                    'guard_name' => 'web',
                ]);
            }
        }
    }
}
```

### 2. RoleSeeder (refactorizado)

**Archivo:** `database/seeders/RoleSeeder.php`

Responsabilidades:
- Limpiar roles existentes con `Role::truncate()`.
- Crear los 4 roles y sincronizar sus permisos con `syncPermissions`.

Matriz de permisos por rol:

| Rol | Acciones permitidas |
|-----|---------------------|
| `admin` | `crear`, `ver`, `editar`, `eliminar` — en todos los módulos (76 permisos) |
| `colaborador` | `crear`, `ver`, `editar` — en todos los módulos (57 permisos) |
| `practicante` | `crear`, `ver` — en todos los módulos (38 permisos) |
| `invitado` | `ver` — en todos los módulos (19 permisos) |

```php
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    private const MODULOS = [
        'avisos', 'biblioteca', 'calendario', 'capital-humano', 'citas',
        'contactos', 'coordinaciones', 'correos', 'documentos', 'eventos',
        'gerencia', 'mantenimiento', 'movilidad', 'proyectos', 'seguridad',
        'servicios-escolares', 'tics', 'titulacion', 'usuarios',
    ];

    private function permisosParaAcciones(array $acciones): array
    {
        $permisos = [];
        foreach (self::MODULOS as $modulo) {
            foreach ($acciones as $accion) {
                $permisos[] = "{$modulo}.{$accion}";
            }
        }
        return $permisos;
    }

    public function run(): void
    {
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();
        Role::truncate();

        $mapa = [
            'admin'       => ['crear', 'ver', 'editar', 'eliminar'],
            'colaborador' => ['crear', 'ver', 'editar'],
            'practicante' => ['crear', 'ver'],
            'invitado'    => ['ver'],
        ];

        foreach ($mapa as $nombreRol => $acciones) {
            $role = Role::firstOrCreate(['name' => $nombreRol, 'guard_name' => 'web']);
            $role->syncPermissions($this->permisosParaAcciones($acciones));
        }
    }
}
```

### 3. DatabaseSeeder (orden de dependencia)

**Archivo:** `database/seeders/DatabaseSeeder.php`

```php
public function run(): void
{
    // PermissionSeeder DEBE ejecutarse antes de RoleSeeder
    $this->call([
        PermissionSeeder::class,
        RoleSeeder::class,
    ]);
}
```

### 4. Rutas protegidas con middleware

**Archivo:** `routes/web.php`

```php
use App\Http\Controllers\admin\UserController;
use App\Http\Controllers\admin\RolesController;

// Rutas de administración de usuarios y roles
Route::middleware(['auth', 'permission:usuarios.ver'])->group(function () {
    Route::resource('admin/users', UserController::class)->names('admin.users');
});

Route::middleware(['auth', 'permission:roles.ver'])->group(function () {
    Route::resource('admin/roles', RolesController::class)->names('admin.roles');
});

// Protección granular por acción (sobre las rutas de cada módulo futuro)
// Patrón recomendado para cada módulo:
// Route::middleware(['auth', 'permission:avisos.ver'])->group(function () {
//     Route::get('/avisos', [AvisosController::class, 'index']);
//     Route::middleware('permission:avisos.crear')->post('/avisos', [..., 'store']);
//     Route::middleware('permission:avisos.editar')->put('/avisos/{id}', [..., 'update']);
//     Route::middleware('permission:avisos.eliminar')->delete('/avisos/{id}', [..., 'destroy']);
// });
```

**Comportamiento del middleware `permission` de Spatie:**
- Usuario autenticado sin permiso → HTTP 403 (`AuthorizationException`).
- Usuario no autenticado → redirige a `route('login')` (manejado por middleware `auth`).

### 5. Dashboard con visibilidad dinámica

**Archivo:** `resources/views/dashboard/home.blade.php`

Cada tarjeta se envuelve con `@can('modulo.ver') ... @endcan`. Si el usuario no tiene ese permiso, Blade omite el bloque silenciosamente.

```blade
@extends("layouts.main")

@section('contenido')
<main class="panel container" id="panel">

    @can('avisos.ver')
    <div class="card area-1">
        <a href="#">
            <img src="{{ asset('iconos-secciones/avisos.png') }}" alt="">
            <h2 class="title-card">AVISOS</h2>
        </a>
    </div>
    @endcan

    @can('biblioteca.ver')
    <div class="card area-1">
        <a href="#">
            <img src="{{ asset('iconos-secciones/biblioteca.png') }}" alt="">
            <h2 class="title-card">BIBLIOTECA</h2>
        </a>
    </div>
    @endcan

    {{-- ... (una tarjeta @can por cada uno de los 19 módulos) --}}

    @can('usuarios.ver')
    <div class="card area-4">
        <a href="{{ route('admin.users.index') }}">
            <img src="{{ asset('iconos-secciones/personal.png') }}" alt="">
            <h2 class="title-card">USUARIOS</h2>
        </a>
    </div>
    @endcan

</main>
@endsection
```

**El controlador `Dashboard` no necesita cambios**: la lógica de visibilidad es totalmente declarativa en la vista.

### 6. UserController (actualizado)

Cambios respecto al estado actual:

- **`store`**: Mantener validación existente. Cambiar asignación de rol a `$user->syncRoles($request->input('role'))` para garantizar que solo un rol quede asignado.
- **`update`**: Misma lógica. `$user->syncRoles($request->input('role', []))`.
- **`destroy`**: El modelo de Spatie desvincula automáticamente roles y permisos al eliminar el usuario gracias al trait `HasRoles`. No requiere cambios adicionales.

```php
// En store():
$user = User::create($data);
if ($request->filled('role')) {
    $user->syncRoles($request->input('role'));
}

// En update():
$user->syncRoles($request->input('role', []));
$user->save();

// En destroy():
$user->delete(); // Spatie limpia model_has_roles automáticamente
```

### 7. RolesController (sin cambios estructurales)

El controlador existente ya implementa correctamente `syncPermissions` en `update`. Los ajustes son menores:

- **`store`**: Ya usa `$role->permissions()->attach(...)`. Cambiar a `$role->syncPermissions(...)` para consistencia con `update`.
- **`destroy`**: Verificar que Spatie limpia `role_has_permissions` y `model_has_roles` automáticamente al eliminar el rol (sí lo hace con el Observer interno de Spatie).

---

## Data Models

### Tablas de Spatie (ya migradas)

```
permissions
  id | name (modulo.accion) | guard_name | created_at | updated_at

roles
  id | name | guard_name | created_at | updated_at

model_has_permissions
  permission_id | model_type | model_id

model_has_roles
  role_id | model_type | model_id

role_has_permissions
  permission_id | role_id
```

### Tabla `users` (columnas adicionales existentes)

```
users
  id | name | correo | telefono | profesion | empleado (unique) | email (unique) 
     | password | email_verified_at | remember_token | created_at | updated_at
```

### Mapa de permisos por módulo

```
Módulo              crear  ver  editar  eliminar
avisos                ✓     ✓     ✓       ✓
biblioteca            ✓     ✓     ✓       ✓
calendario            ✓     ✓     ✓       ✓
capital-humano        ✓     ✓     ✓       ✓
citas                 ✓     ✓     ✓       ✓
contactos             ✓     ✓     ✓       ✓
coordinaciones        ✓     ✓     ✓       ✓
correos               ✓     ✓     ✓       ✓
documentos            ✓     ✓     ✓       ✓
eventos               ✓     ✓     ✓       ✓
gerencia              ✓     ✓     ✓       ✓
mantenimiento         ✓     ✓     ✓       ✓
movilidad             ✓     ✓     ✓       ✓
proyectos             ✓     ✓     ✓       ✓
seguridad             ✓     ✓     ✓       ✓
servicios-escolares   ✓     ✓     ✓       ✓
tics                  ✓     ✓     ✓       ✓
titulacion            ✓     ✓     ✓       ✓
usuarios              ✓     ✓     ✓       ✓
Total: 19 × 4 = 76
```

---

## Interfaces

### Formulario de creación/edición de usuario

**Vista:** `resources/views/admin/users/create.blade.php` (y `edit.blade.php`)

```html
<!-- Selección de rol único (radio o select) -->
<select name="role" class="form-select">
    @foreach($roles as $role)
        <option value="{{ $role->name }}"
            {{ old('role', $user->roles->first()?->name ?? '') === $role->name ? 'selected' : '' }}>
            {{ ucfirst($role->name) }}
        </option>
    @endforeach
</select>
```

### Formulario de creación/edición de rol

**Vista:** `resources/views/admin/roles/create.blade.php` (y `edit.blade.php`)

```html
<!-- Listado de permisos con checkboxes agrupados por módulo -->
@foreach($permisosPorModulo as $modulo => $permisos)
<fieldset>
    <legend>{{ ucfirst($modulo) }}</legend>
    @foreach($permisos as $permiso)
    <label>
        <input type="checkbox" name="permissions[]" value="{{ $permiso->id }}"
            {{ in_array($permiso->id, $permisosDelRol ?? []) ? 'checked' : '' }}>
        {{ $permiso->name }}
    </label>
    @endforeach
</fieldset>
@endforeach
```

### Respuestas HTTP del middleware

| Situación | Respuesta |
|-----------|-----------|
| Usuario no autenticado | Redirect 302 → `/login` |
| Usuario autenticado sin permiso | HTTP 403 (página de error de Laravel) |
| Usuario autenticado con permiso | Continúa hacia el controlador |

---

## Error Handling

### Middleware 403

Laravel renderiza automáticamente la vista `errors/403.blade.php` cuando se lanza `AuthorizationException`. Se recomienda crear esa vista para mantener el branding de Arrax ERP:

```blade
{{-- resources/views/errors/403.blade.php --}}
@extends('layouts.main')
@section('contenido')
<div class="container text-center mt-5">
    <h1>403</h1>
    <p>No tienes permiso para acceder a esta sección.</p>
    <a href="{{ route('dashboard.index') }}" class="btn btn-primary">Volver al Dashboard</a>
</div>
@endsection
```

### Validaciones de UserController

| Campo | Regla | Mensaje esperado |
|-------|-------|-----------------|
| `name` | `required|string|max:255` | El nombre es obligatorio |
| `email` | `required|email|unique:users,email,{id}` | Email ya registrado |
| `empleado` | `required|integer|unique:users,empleado,{id}` | Número de empleado duplicado |
| `telefono` | `required|integer|digits:10` | Teléfono debe tener 10 dígitos |
| `password` | `nullable|string|min:8|confirmed` | Mínimo 8 caracteres, debe coincidir |

### Validaciones de RolesController

| Campo | Regla | Mensaje esperado |
|-------|-------|-----------------|
| `name` | `required|unique:roles,name,{id}` | Nombre de rol ya en uso |
| `permissions` | `required|array` | Debe seleccionar al menos un permiso |
| `permissions.*` | `exists:permissions,id` | Permiso inválido |

### Truncate seguro en Seeders

Al usar `Permission::truncate()` y `Role::truncate()`, la clave foránea en `role_has_permissions` causaría error si no se deshabilitan temporalmente. El enfoque correcto:

```php
// En cada Seeder, antes del truncate:
\DB::statement('SET FOREIGN_KEY_CHECKS=0;');  // MySQL
Permission::truncate();
\DB::statement('SET FOREIGN_KEY_CHECKS=1;');

// Para SQLite (no soporta FK_CHECKS, pero SQLite no tiene FK enforcement por defecto):
\DB::statement('PRAGMA foreign_keys = OFF;');
Permission::truncate();
\DB::statement('PRAGMA foreign_keys = ON;');
```

---

## Correctness Properties

*Una propiedad es una característica o comportamiento que debe cumplirse en todas las ejecuciones válidas del sistema — esencialmente, una afirmación formal sobre lo que el sistema debe hacer. Las propiedades sirven como puente entre especificaciones en lenguaje natural y garantías de corrección verificables automáticamente.*

---

### Property 1: Idempotencia del PermissionSeeder

*Para cualquier* estado previo de la tabla `permissions` (vacía, con permisos del formato antiguo o con permisos parciales), ejecutar el `PermissionSeeder` dos veces consecutivas debe producir exactamente 76 registros en `permissions`, todos con formato `modulo.accion` y `guard_name = 'web'`, sin lanzar excepción.

**Validates: Requirements 1.1, 1.2, 1.3, 1.4**

---

### Property 2: Restricción de permisos del rol `colaborador`

*Para cualquier* permiso asignado al rol `colaborador` después de ejecutar el `RoleSeeder`, la parte de la acción en el nombre del permiso (`modulo.accion`) no debe ser `eliminar`.

**Validates: Requirements 2.3**

---

### Property 3: Restricción de permisos del rol `practicante`

*Para cualquier* permiso asignado al rol `practicante` después de ejecutar el `RoleSeeder`, la acción del permiso debe ser exclusivamente `crear` o `ver`.

**Validates: Requirements 2.4**

---

### Property 4: Restricción de permisos del rol `invitado`

*Para cualquier* permiso asignado al rol `invitado` después de ejecutar el `RoleSeeder`, la acción del permiso debe ser exclusivamente `ver`.

**Validates: Requirements 2.5**

---

### Property 5: Idempotencia del RoleSeeder

*Para cualquier* estado previo de la tabla `roles`, ejecutar el `RoleSeeder` dos veces consecutivas debe producir exactamente 4 roles (`admin`, `colaborador`, `practicante`, `invitado`) sin duplicados.

**Validates: Requirements 2.1, 2.6**

---

### Property 6: Control de acceso por módulo y acción (middleware 403)

*Para cualquier* módulo de los 19 definidos y cualquier acción (`ver`, `crear`, `editar`, `eliminar`), un usuario autenticado que no tenga asignado el permiso `modulo.accion` correspondiente debe recibir una respuesta HTTP 403 al intentar acceder a la ruta protegida con ese permiso.

**Validates: Requirements 3.1, 3.2, 3.3, 3.4**

---

### Property 7: Redirección a login para usuarios no autenticados

*Para cualquier* ruta del sistema protegida con el middleware `auth`, un usuario no autenticado debe recibir una redirección HTTP 302 hacia la ruta de login, independientemente del módulo o acción involucrada.

**Validates: Requirements 3.5**

---

### Property 8: Visibilidad de tarjetas del dashboard según permisos `.ver`

*Para cualquier* usuario autenticado con cualquier subconjunto de permisos `modulo.ver`, el dashboard debe renderizar exactamente las tarjetas correspondientes a los módulos con permiso concedido: ni más ni menos. La cantidad de tarjetas visibles debe igualar la cantidad de permisos `*.ver` activos del usuario.

**Validates: Requirements 4.1, 4.2, 4.4, 4.5**

---

### Property 9: Sincronización de rol en actualización de usuario

*Para cualquier* usuario existente con un rol asignado y cualquier nuevo rol seleccionado en el formulario de edición, después de la actualización el usuario debe tener asignado **únicamente** el nuevo rol, sin rastros del rol anterior en `model_has_roles`.

**Validates: Requirements 5.5**

---

### Property 10: Limpieza de relaciones al eliminar usuario

*Para cualquier* usuario con cualquier conjunto de roles asignados, al eliminar el usuario el sistema debe eliminar el registro en `users` y todos los registros asociados en `model_has_roles` para ese usuario, sin dejar registros huérfanos.

**Validates: Requirements 5.6**

---

### Property 11: Sincronización de permisos en actualización de rol

*Para cualquier* rol existente con un conjunto de permisos asignados y cualquier nuevo conjunto de permisos seleccionado en el formulario de edición, después de la actualización el rol debe tener asignado **únicamente** el nuevo conjunto de permisos en `role_has_permissions`, sin permisos del conjunto anterior.

**Validates: Requirements 6.4**

---

### Property 12: Limpieza de relaciones al eliminar rol

*Para cualquier* rol con cualquier conjunto de usuarios asignados, al eliminar el rol el sistema debe eliminar todos los registros en `model_has_roles` donde `role_id` corresponde al rol eliminado, de forma que ningún usuario quede con ese rol asignado.

**Validates: Requirements 6.5**

---

### Property 13: Migración limpia — ausencia de permisos con formato antiguo

*Para cualquier* estado previo de la BD que contenga permisos con el formato antiguo (ej. `acceso usuarios`, `acceso gerencia`), después de ejecutar el `PermissionSeeder` ningún registro en `permissions` debe tener un nombre con el patrón `acceso *` (sin punto).

**Validates: Requirements 7.1, 7.2**

---

### Property 14: Validación de unicidad del nombre de rol

*Para cualquier* nombre de rol que ya exista en la tabla `roles`, intentar crear un nuevo rol con ese mismo nombre debe retornar un error de validación (respuesta con código 422 o redirección con errores), sin insertar un registro duplicado en la tabla.

**Validates: Requirements 6.3, 6.6**
