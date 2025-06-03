# Sistema de Gestión de Recursos Humanos para Cooperativas

Este proyecto es un sistema web desarrollado en Laravel para la gestión integral de recursos humanos en cooperativas. Permite administrar trabajadores, licencias, vacaciones, usuarios y la importación de nóminas desde archivos Excel, entre otras funcionalidades.

## Funcionalidades principales

- **Gestión de Trabajadores:** Altas, bajas, edición y visualización de legajos, datos personales, laborales y bancarios.
- **Gestión de Licencias:** Solicitud, aprobación y seguimiento de licencias para trabajadores.
- **Gestión de Vacaciones:** Solicitud, aprobación y control de días de vacaciones.
- **Importación de Nómina:** Carga masiva de datos de trabajadores desde archivos Excel.
- **Gestión de Usuarios:** Administración de usuarios del sistema y su vinculación con trabajadores.
- **Paneles diferenciados:** Acceso para administradores y portal para trabajadores.

## Requisitos

- PHP >= 8.2
- Composer
- Node.js y npm
- Base de datos MySQL/MariaDB o compatible

## Instalación

1. Clona el repositorio:
   ```bash
   git clone https://github.com/fedejfernandez/cooperativa-rrhh.git
   cd cooperativa-rrhh
   ```
2. Instala las dependencias de PHP y JavaScript:
   ```bash
   composer install
   npm install
   ```
3. Copia el archivo de entorno y configura tus variables:
   ```bash
   cp .env.example .env
   # Edita .env con tus datos de base de datos y correo
   ```
4. Genera la clave de la aplicación:
   ```bash
   php artisan key:generate
   ```
5. Ejecuta las migraciones y (opcional) los seeders:
   ```bash
   php artisan migrate
   # php artisan db:seed
   ```
6. Compila los assets:
   ```bash
   npm run build
   # o para desarrollo
   npm run dev
   ```
7. Inicia el servidor de desarrollo:
   ```bash
   php artisan serve
   ```

## Uso rápido

- Accede a `/admin` para el panel de administración (requiere usuario administrador).
- Los trabajadores pueden acceder a su portal para solicitar licencias y vacaciones.
- Para importar nómina: `php artisan nomina:importar <archivo.xlsx>`

## Créditos

Desarrollado por **Fedejfernandez**

---

Este sistema está basado en Laravel y utiliza Livewire para componentes interactivos. Puedes personalizarlo según las necesidades de tu cooperativa.
