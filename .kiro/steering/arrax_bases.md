---
inclusion: always
---

# Arrax ERP — Visión y Reglas del Proyecto

ERP educativo (escuela/universidad) construido en Laravel. Agrupa departamentos institucionales en módulos accesibles desde un dashboard central, con autenticación y control de acceso basado en roles.

## Roles y Permisos

Los roles definen el nivel de acceso. Cada permiso se nombra en español.

| Rol | Permisos | Módulos |
|---|---|---|
| `admin` | Todos | Todos, incluido Usuarios y Roles/Permisos |
| `encargado` | Crear, Ver, Editar, Eliminar | Su departamento + compartidos |
| `auxiliar` | Crear, Ver, Editar | Su departamento + compartidos |
| `practicante` | Crear, Ver, Editar | Su departamento + compartidos |
| `invitado` | Ver | Solo módulos compartidos |

**Regla crítica:** Solo el `admin` tiene acceso total. Cada módulo de departamento es exclusivo para el admin + el encargado, auxiliar y practicante de ese departamento. Los módulos compartidos son accesibles para todos los roles.

**Regla de visibilidad de datos:** Dentro de un módulo, cada usuario solo ve los registros que él mismo creó. El `admin` puede ver, editar y eliminar registros de cualquier usuario.

## Estado de Módulos

### Módulos Generales (solo su departamento + admin)

| # | Módulo | Estado |
|---|---|---|
| 1 | Roles / Permisos | ✅ CRUD completado |
| 2 | Usuarios | 🔄 CRUD en proceso |
| 3 | Avisos | ❌ Sin construir |
| 4 | Biblioteca | ❌ Sin construir |
| 5 | Calendario | ❌ Sin construir |
| 6 | Capital Humano | ❌ Sin construir |
| 7 | Citas | ❌ Sin construir |
| 8 | Contactos | ❌ Sin construir |
| 9 | Coordinaciones | ❌ Sin construir |
| 10 | Correos | ❌ Sin construir |
| 11 | Documentos | ❌ Sin construir |
| 12 | Eventos | ❌ Sin construir |
| 13 | Gerencia | ❌ Sin construir |
| 14 | Mantenimiento | ❌ Sin construir |
| 15 | Movilidad | ❌ Sin construir |
| 16 | Proyectos | ❌ Sin construir |
| 17 | Seguridad | ❌ Sin construir |
| 18 | Servicios Escolares | ❌ Sin construir |
| 19 | TICs | ❌ Sin construir |
| 20 | Tickets (Soporte técnico) | ❌ Sin construir |
| 21 | Titulación | ❌ Sin construir |

### Módulos Compartidos (acceso para todos los roles)

Avisos, Calendario, Citas, Contactos, Correos, Documentos, Eventos, Tickets.

## Arquitectura y Convenciones de Código

- **Un módulo a la vez:** No iniciar un nuevo módulo hasta que el actual esté completamente funcional.
- **Controladores:** Namespacing por área — `App\Http\Controllers\admin\` para admin, `App\Http\Controllers\Auth\` para autenticación.
- **Vistas:** Extienden `layouts.main` con `@section('contenido')` / `@yield('contenido')`. Vistas admin, vistas poublic por seraprado, una carpeta por modulo con sus respectivas vistas CRUD.  
- **Rutas:** Nombradas como `admin.{recurso}.{acción}` (ej. `admin.users.index`).
- **Permisos:** Strings en español (ej. `acceso usuarios`, `acceso crear`, `acceso eliminar`).
- **Flash messages:** `->with('success', '...')` / `->with('error', '...')`, renderizados en `main.blade.php`.
- **Paginación:** `->paginate(N)` con `{{ $items->links() }}` en las vistas.
- **Modelo User:** Usa atributos PHP 8 `#[Fillable([...])]` y `#[Hidden([...])]` en lugar de propiedades `$fillable`/`$hidden`.
- **Comentarios:** Marcadores de sección en PHP usan estilo `//=======` y se escriben en español, mencionando los cambios o secciones, y una breve explicación.
- **Estilos:** Bootstrap 5.3 es el sistema principal. SCSS compilado vía Vite (`resources/sass/app.scss`). No introducir utilidades de Tailwind en componentes donde Bootstrap ya aplique.
- **Componentes UI:** Formularios, cards, botones y modales siguen los patrones definidos en `arrax_componente.md` y(`resources/sass/app.scss`)..

## Funcionalidades Planeadas

Login con autenticación, CRUD por módulo, módulos enlazados entre sí, historial de acciones por usuario, calendario de eventos/citas, contabilidad y pagos, chat en vivo, correos internos, gestión documental, seguimiento de proyectos y tareas, sistema de tickets, reportes periódicos por área, y registro académico (materias, docentes, calificaciones, alumnos).

## Comportamiento del Agente

- Actuar como tutor: orientar y aconsejar en lugar de resolver el problema directamente.
- Ante errores o dudas, presentar opciones con justificación antes de implementar.
- Mostrar el plan de acciones y esperar confirmación del usuario antes de ejecutar cambios.
- Al implementar ajustes, comentar el cambio realizado y una breve explicación.
- Razonar internamente antes de responder; priorizar la corrección y las buenas practicas sobre la velocidad.
