# Project Structure

## Root Layout

```
arrax_erp/
├── app/                    # PHP application code
├── bootstrap/              # Laravel bootstrap files
├── config/                 # Config files (app, auth, database, permission, etc.)
├── database/               # Migrations, seeders, factories
├── lang/                   # Language files (en, es)
├── public/                 # Web root — compiled assets, images, favicon
│   ├── build/              # Vite output
│   └── iconos-secciones/   # Section icon images
├── resources/              # Frontend source files
├── routes/                 # Route definitions
├── storage/                # Logs, cache, uploads
└── tests/                  # PHPUnit tests
```

## App Directory

```
app/
├── Http/
│   └── Controllers/
│       ├── Controller.php          # Base controller
│       ├── Dashboard.php           # Dashboard controller (auth middleware)
│       ├── admin/                  # Admin-only controllers
│       │   ├── UserController.php
│       │   ├── RolesController.php
│       │   └── PermissionController.php
│       ├── Auth/                   # Laravel UI auth controllers
│       └── public/                 # Public-facing controllers (unauthenticated)
├── Models/
│   └── User.php                    # Uses HasRoles trait from Spatie
└── Providers/
    └── AppServiceProvider.php
```

## Resources / Views

```
resources/
├── sass/           # SCSS source (app.scss is the entry point)
├── js/             # JS source (app.js is the entry point)
├── css/
├── images/
└── views/
    ├── layouts/
    │   ├── main.blade.php      # Main authenticated layout (navbar, footer, @yield('contenido'))
    │   └── app.blade.php       # Auth layout (login/register)
    ├── dashboard/              # Dashboard views
    ├── admin/
    │   ├── users/              # CRUD views for users (index, create, edit, show)
    │   └── roles/              # CRUD views for roles (index, create, edit, show)
    ├── auth/                   # Login, register views
    └── public/                 # Public/unauthenticated views
```

## Database

```
database/
├── migrations/         # Schema definitions
├── seeders/
│   ├── DatabaseSeeder.php
│   ├── PermissionSeeder.php   # Creates all permission records
│   └── RoleSeeder.php         # Creates roles and syncs permissions
└── factories/
    └── UserFactory.php
```

## Key Conventions

- **Controllers** are namespaced by area: `App\Http\Controllers\admin\` for admin, `App\Http\Controllers\Auth\` for auth.
- **Blade layout**: authenticated views extend `layouts.main` and use `@section('contenido')` / `@yield('contenido')`.
- **Comments**: inline PHP comments use `//=======` style section markers in Spanish.
- **Flash messages**: passed via `->with('success', '...')` and rendered in `main.blade.php` as `session('success')`.
- **Pagination**: collections use `->paginate(N)` and are displayed with `{{ $items->links() }}` in views.
- **Route naming**: admin routes follow the pattern `admin.{resource}.{action}` (e.g. `admin.users.index`).
- **User model**: uses PHP 8 attribute syntax `#[Fillable([...])]` and `#[Hidden([...])]` instead of `$fillable`/`$hidden` properties.
