# Arrax ERP

Arrax ERP is a web-based enterprise resource planning system for organizational management. It provides a role-based dashboard with modules for users, roles, permissions, and various organizational sections (correo, mensajes, soporte técnico, gerencia, proyectos, etc.).

## Core Domain

- **Auth**: Login/logout via Laravel UI (session-based, no API tokens).
- **Users**: Employee accounts with custom fields — `name`, `correo`, `telefono`, `profesion`, `empleado` (employee number).
- **Roles & Permissions**: Managed via Spatie Laravel Permission. Four built-in roles: `admin`, `auxiliar`, `practicante`, `invitado`.
- **Permissions**: Spanish-language permission strings (e.g. `acceso usuarios`, `acceso crear`, `acceso ver`, `acceso editar`, `acceso actualizar`, `acceso eliminar`, `acceso gerencia`, `acceso proyectos`, `acceso roles`).

## Language

The application UI and database content is primarily in Spanish. Permission names, flash messages, and validation feedback are written in Spanish.
