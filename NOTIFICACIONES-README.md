# 📧 Sistema de Notificaciones - RRHH Cooperativa

## 🎯 Descripción General

Se ha implementado un sistema completo de notificaciones para el sistema de RRHH que permite la comunicación automática entre administradores y trabajadores sobre eventos importantes relacionados con vacaciones y licencias.

## ✨ Características Principales

### 🔔 Centro de Notificaciones
- **Dropdown interactivo** en la barra de navegación
- **Contador de notificaciones no leídas** con badge visual
- **Vista previa** de las últimas 10 notificaciones
- **Acciones rápidas**: marcar como leída, eliminar, ir a detalles
- **Indicadores visuales** para notificaciones no leídas
- **Timestamps relativos** (hace 2 horas, ayer, etc.)

### 📬 Tipos de Notificaciones

#### Vacaciones
1. **Nueva Solicitud de Vacación** (Para Administradores)
   - Se envía cuando un trabajador solicita vacaciones
   - Incluye datos del trabajador y fechas solicitadas
   - Enlace directo a la gestión de solicitudes

2. **Vacación Aprobada** (Para Trabajadores)
   - Se envía cuando el administrador aprueba una solicitud
   - Incluye fechas aprobadas y comentarios del admin
   - Enlace al portal de vacaciones

3. **Vacación Rechazada** (Para Trabajadores)
   - Se envía cuando el administrador rechaza una solicitud
   - Incluye motivo del rechazo
   - Enlace al portal de vacaciones

#### Licencias
1. **Nueva Solicitud de Licencia** (Para Administradores)
   - Se envía cuando un trabajador solicita una licencia
   - Incluye tipo de licencia y fechas
   - Enlace directo a la gestión de licencias

2. **Licencia Aprobada** (Para Trabajadores)
   - Se envía cuando se aprueba una licencia
   - Incluye tipo y fechas aprobadas
   - Enlace al portal de licencias

3. **Licencia Rechazada** (Para Trabajadores)
   - Se envía cuando se rechaza una licencia
   - Incluye motivo del rechazo
   - Enlace al portal de licencias

## 🏗️ Arquitectura Técnica

### Componentes Principales

#### 1. Notificaciones (app/Notifications/)
```
├── NuevaSolicitudVacacion.php    # Para administradores
├── VacacionAprobada.php          # Para trabajadores
├── VacacionRechazada.php         # Para trabajadores
├── NuevaSolicitudLicencia.php    # Para administradores
├── LicenciaAprobada.php          # Para trabajadores
└── LicenciaRechazada.php         # Para trabajadores
```

#### 2. Centro de Notificaciones (Livewire)
```
├── app/Livewire/NotificationCenter.php
└── resources/views/livewire/notification-center.blade.php
```

#### 3. Integración en Componentes
- **Portal/Vacaciones/Create.php**: Envía notificaciones a admins
- **Admin/Vacaciones/Requests.php**: Envía notificaciones a trabajadores
- Similar integración para licencias

### Canales de Entrega
- **Base de Datos**: Para el centro de notificaciones en tiempo real
- **Email**: Para notificaciones importantes vía correo electrónico
- **Cola de Trabajos**: Procesamiento asíncrono con `ShouldQueue`

## 🚀 Funcionalidades del Centro de Notificaciones

### Características UI/UX
- **Diseño Responsivo**: Funciona en desktop y móvil
- **Iconos Contextuales**: Diferentes iconos según el tipo de notificación
- **Colores Temáticos**: Verde (aprobado), rojo (rechazado), azul (nuevo), etc.
- **Animaciones Suaves**: Transiciones al abrir/cerrar dropdown
- **Estados Visuales**: Diferenciación clara entre leídas y no leídas

### Funcionalidades de Gestión
```javascript
// Funciones principales del componente
- loadNotifications()      // Cargar notificaciones del usuario
- markAsRead($id)         // Marcar notificación como leída
- markAllAsRead()         // Marcar todas como leídas
- deleteNotification($id) // Eliminar notificación
- goToNotification($id)   // Ir a la página relacionada
```

## 🔧 Configuración e Instalación

### 1. Migraciones Requeridas
```bash
php artisan notifications:table
php artisan migrate
```

### 2. Configuración de Colas (Opcional pero Recomendado)
```bash
# En .env
QUEUE_CONNECTION=database

# Crear tabla de trabajos
php artisan queue:table
php artisan migrate

# Ejecutar worker de colas
php artisan queue:work
```

### 3. Configuración de Email
```bash
# En .env configurar driver de email
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="noreply@cooperativa.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## 🧪 Testing y Comandos de Prueba

### Comando de Notificaciones de Prueba
```bash
# Generar todas las notificaciones para el primer usuario
php artisan notifications:test

# Generar notificaciones para un usuario específico
php artisan notifications:test 1

# Generar solo notificaciones de vacaciones
php artisan notifications:test --type=vacaciones

# Generar solo notificaciones de licencias
php artisan notifications:test --type=licencias
```

## 📊 Estructura de Datos

### Formato de Notificación en Base de Datos
```json
{
  "tipo": "nueva_solicitud_vacacion",
  "titulo": "Nueva Solicitud de Vacaciones",
  "mensaje": "Juan Pérez ha solicitado vacaciones del 15/01/2024 al 25/01/2024",
  "datos": {
    "vacacion_id": 123,
    "trabajador_id": 456,
    "trabajador_nombre": "Juan Pérez",
    "fecha_inicio": "2024-01-15",
    "fecha_fin": "2024-01-25",
    "dias_solicitados": 8,
    "url": "/admin/vacaciones/requests"
  },
  "icono": "calendar",
  "color": "blue"
}
```

## 🎨 Personalización Visual

### Iconos Disponibles
- `calendar`: Eventos relacionados con fechas
- `check-circle`: Aprobaciones
- `x-circle`: Rechazos
- `document-text`: Documentos/licencias
- `bell`: Notificaciones generales

### Colores Temáticos
- `blue`: Nuevas solicitudes
- `green`: Aprobaciones
- `red`: Rechazos
- `purple`: Licencias
- `gray`: General

## 📈 Próximas Mejoras

### Funcionalidades Pendientes
1. **Notificaciones Push**: Implementar WebSockets para notificaciones en tiempo real
2. **Configuración Personal**: Permitir a usuarios elegir qué notificaciones recibir
3. **Historial Completo**: Página dedicada para ver todas las notificaciones
4. **Notificaciones de Recordatorio**: Avisos antes de vencimientos
5. **Integración con Slack/Teams**: Canales adicionales de notificación

### Optimizaciones Técnicas
1. **Cache de Notificaciones**: Implementar cache para mejorar rendimiento
2. **Paginación**: Para usuarios con muchas notificaciones
3. **Filtros Avanzados**: Por tipo, fecha, estado, etc.
4. **Notificaciones por Roles**: Diferentes notificaciones según el rol del usuario

## 🛡️ Seguridad

### Aspectos de Seguridad Implementados
- **Autorización**: Solo se envían notificaciones a usuarios autorizados
- **Validación de Datos**: Todos los datos se validan antes del envío
- **Escape de HTML**: Prevención de XSS en el contenido
- **Rate Limiting**: Prevención de spam de notificaciones

## 📱 Integración con el Sistema

### Ubicación en la Interfaz
- **Barra de Navegación**: Icono de campana junto al perfil del usuario
- **Responsive**: Visible en desktop y móvil
- **Contexto**: Disponible en todas las páginas del sistema

### Flujo de Trabajo
1. **Acción del Usuario**: Trabajador solicita vacaciones/licencias
2. **Trigger Automático**: Sistema detecta la acción
3. **Envío de Notificación**: Se envía a usuarios correspondientes
4. **Recepción Visual**: Badge de notificación aparece
5. **Interacción**: Usuario puede ver, marcar como leída o ir a detalles

## 🔗 Enlaces Importantes

### Rutas del Sistema
- `/admin/vacaciones/requests` - Gestión de solicitudes de vacaciones
- `/admin/licencias/requests` - Gestión de solicitudes de licencias
- `/portal/vacaciones/index` - Portal de vacaciones para trabajadores
- `/portal/licencias/index` - Portal de licencias para trabajadores

### Archivos Clave
- `resources/views/navigation-menu.blade.php` - Integración del centro de notificaciones
- `app/Models/User.php` - Modelo con trait `Notifiable`
- `database/migrations/*_create_notifications_table.php` - Tabla de notificaciones

---

**Desarrollado para el Sistema de RRHH de la Cooperativa** 🏢  
*Sistema completo de gestión de notificaciones para mejorar la comunicación entre administradores y trabajadores.* 