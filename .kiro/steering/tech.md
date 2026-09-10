# Tech Stack

## Backend
- **PHP** ^8.3
- **Laravel** ^13.17
- **Laravel UI** ^4.6 — auth scaffolding (Blade-based)
- **Spatie Laravel Permission** ^8.3 — roles & permissions
- **Laravel Lang** ^15.34 — multilingual support

## Frontend
- **Blade** templates
- **Bootstrap** 5.3.x — layout and components
- **Bootstrap Icons** (CDN)
- **Iconify** (CDN, `solar:*` icon set)
- **Sass/SCSS** compiled via Vite (`resources/sass/app.scss`)
- **Tailwind CSS** ^4.0 (installed but Sass/Bootstrap is the primary styling system)
- **Poppins** (Google Fonts) — primary typeface

## Build Tools
- **Vite** ^8.0 with `laravel-vite-plugin`
- **pnpm** (workspace configured via `pnpm-workspace.yaml`)

## Dev Tools
- **Laravel Pint** — PHP code formatter
- **PHPUnit** ^12 — testing
- **Laravel Pail** — log viewer
- **Faker** — test data

## Database
- **MySQL** (XAMPP, primary)
- **SQLite** (`database/database.sqlite`) — available as fallback

---

## Common Commands

```bash
# Install dependencies
composer install
pnpm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database
php artisan migrate
php artisan migrate:fresh --seed   # reset and seed with roles/permissions

# Seed only
php artisan db:seed --class=PermissionSeeder
php artisan db:seed --class=RoleSeeder

# Build frontend assets
pnpm run build        # production
pnpm run dev          # development (run manually in terminal)

# Code formatting
./vendor/bin/pint

# Tests
php artisan test

# Cache
php artisan config:clear
php artisan cache:clear
php artisan view:clear
```
