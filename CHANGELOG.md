# 📝 Historial de Cambios (Changelog)

---

## OBJETIVOS DEL PROYECTO
> **Sistema:** Web de Gestión Académica – U.E. Modelo Tiquipaya, Bolivia
> **Stack:** Laravel 12 · PHP 8.2 · MySQL 8.0 · Bootstrap 5 · Blade Templates

### Objetivo General
Desarrollar un sistema web de gestión académico para la inscripción de estudiantes en la
Unidad Educativa Modelo Tiquipaya, con el fin de mejorar la eficiencia del trabajo de los
docentes y administrativos, optimizando los procesos de inscripción y gestión de información
estudiantil.

### Objetivos Específicos
1. **Tecnología:** Indagar herramientas de desarrollo adecuadas que garanticen rendimiento
   y capacidad de crecimiento a largo plazo.
   → *Implementado:* Laravel 12, PHP 8.2, MySQL 8.0 (estable, con soporte largo plazo).

2. **Base de datos:** Incorporar una BD segura y eficiente para almacenar y gestionar la
   información de los estudiantes.
   → *Implementado:* 11 tablas relacionadas en MySQL Workbench, acceso gestionado
   por Eloquent ORM con Eager Loading.

3. **Interfaz centrada en el usuario:** Implementar un diseño de interfaz que facilite la
   navegación y acceso a la información del estudiante de manera rápida y eficiente para
   el personal administrativo.
   → *En progreso:* Backend funcional al 100%. Frontend en Bootstrap 5, colores
   corporativos (#2e7d32 verde, blanco, negro).

4. **Módulo de notificaciones:** Desarrollar un módulo para informar a los padres sobre
   el estado de inscripción y otros avisos importantes.
   → *Implementado:* NotificacionController con envío a todos/nivel/grado/padre específico.
   Auto-notificación al aprobar/rechazar inscripción.

5. **Módulo de reserva:** Elaborar un módulo de reserva para estudiantes nuevos que permita
   el ingreso al sistema web.
   → *Implementado:* Formulario público (/reserva) sin login, con flujo completo
   Reserva → Aprobación → Convertir a Estudiantes → Inscripción.

---

## FLUJOS PRINCIPALES DEL SISTEMA

### Flujo: Estudiante NUEVO
```
1. Padre llena formulario público /reserva (sin login)
2. Admin ve la reserva en /reservas
3. Admin aprueba la reserva (estado: aprobada)
4. Padre se presenta físicamente con documentos
5. Admin hace clic en "Convertir a Estudiantes"
   → Crea el registro de Padre en la BD
   → Crea los registros de Estudiante (tipo='nuevo') automáticamente
6. Admin va a /inscripciones/crear
   → Solo aparecen estudiantes tipo=nuevo sin inscripción en la gestión actual
7. Admin crea la inscripción (estado: pendiente → aprobada)
8. Padre recibe notificación automática
```

### Flujo: Estudiante ANTIGUO
```
1. Admin va a /inscripciones/antiguo
2. Busca al estudiante por nombre (Select2 con AJAX en tiempo real)
3. Al seleccionarlo, el sistema muestra: nombre del padre, teléfono, grado anterior
4. Admin confirma o cambia el grado (puede subir de año)
5. Admin guarda → crea Inscripción directamente en estado 'aprobada'
8. Padre recibe notificación automática
```

### Flujo: Panel Administrativo (Admin/Secretaria)
```
Dashboard    → Estadísticas generales
Estudiantes  → Listado completo con filtros (nivel, grado, tipo, búsqueda)
             → Botón "Ver" → Ficha completa (datos, padre, inscripción, hermanos)
Inscripciones→ Gestión de inscripciones del año actual
Reservas     → Revisión y aprobación de solicitudes de cupo
Padres       → Listado de padres con sus hijos y grados inscritos
Notificaciones → Envío de avisos masivos/filtrados a padres
```

---

## [31/03/2026] – Fixes de Lógica de Negocio + Formulario Inteligente

### Backend Fixes
- **Fix 1 (Flujo Reserva):** `ReservaController::convertirAEstudiantes()` crea automáticamente
  el Padre y los Estudiantes cuando el admin confirma la presencia física. Nuevo botón
  "Convertir a Estudiantes" en la vista de detalle de reserva (solo visible en estado 'aprobada').
  `InscripcionController::create()` ahora filtra: solo muestra `tipo=nuevo` sin inscripción actual.

- **Fix 2 (Panel Estudiantes):** Botón "Ver" → Ficha completa. Nombre completo del padre
  en la columna. Badges de tipo (verde/gris). Nuevo accessor `tipo_calculado` en modelo
  `Estudiante` (cuenta gestiones para auto-determinar nuevo/antiguo).

- **Fix 3 (Form Antiguo):** `estudiantes/create` repropuesto para alumnos que regresan.
  Sin campo `tipo` (hardcodeado a 'antiguo'). Texto aclaratorio para secretarias.

- **Fix 4 (Panel Padres):** `padres/index` muestra cada hijo con su grado inscrito.
  Sección "Hermanos" agregada a la ficha de estudiante.

### Formulario Inteligente (Select2 + AJAX)
- Nueva ruta: `GET /inscripciones/antiguo` → vista con Select2
- Nueva ruta: `POST /inscripciones/antiguo` → crea la Inscripción directamente
- Endpoint AJAX: `GET /api/estudiantes/buscar?q=...` → JSON para Select2
- Al seleccionar el estudiante: muestra nombre del padre, teléfono y grado anterior
- Admin puede cambiar el grado (el sistema lo pre-selecciona con el año anterior)
- Submit crea la `Inscripcion` con estado 'aprobada' directamente

---

## [30/03/2026] – Correcciones Críticas del Backend

- Creación de `estudiantes/show.blade.php` (vista faltante)
- Eliminación de 3 rutas debug expuestas sin auth (`/debug-reservas`, etc.)
- Corrección de nombre de ruta `admin.dashboard` → `dashboard` en `web.php`,
  `AuthController`, `app.blade.php` y `notificaciones/crear.blade.php`
- Limpieza de documentación obsoleta de la carpeta `DOCUMENTACION/`
