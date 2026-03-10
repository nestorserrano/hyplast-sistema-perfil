# Hyplast Sistema de Perfil - Gestión de Perfiles de Usuario

## Descripción
Sistema para gestión de perfiles de usuario, preferencias, configuraciones personales y datos de acceso.

## Características Principales
- 👤 Perfil de usuario completo
- ⚙️ Preferencias personalizables
- 🎨 Selección de tema (claro/oscuro)
- 🌐 Idioma personalizado
- 📧 Gestión de correo electrónico
- 🔐 Cambio de contraseña
- 📱 Información de contacto
- 🏢 Datos de empresa

## Modelos Principales
- **User**: Usuario del sistema
- **Profile**: Perfil extendido
- **Social**: Redes sociales
- **MicrosoftToken**: Tokens de Microsoft

## Funcionalidades
- Actualizar información personal
- Cambiar contraseña
- Configurar preferencias
- Conectar cuenta Microsoft
- Gestionar notificaciones
- Subir foto de perfil

## API Endpoints
```
GET    /api/profile                # Ver perfil
PUT    /api/profile                # Actualizar perfil
PUT    /api/profile/password       # Cambiar contraseña
POST   /api/profile/avatar         # Subir avatar
GET    /api/profile/preferences    # Preferencias
PUT    /api/profile/preferences    # Actualizar preferencias
```

## Preferencias Disponibles
- Tema (claro/oscuro)
- Idioma (es/en)
- Zona horaria
- Notificaciones por email
- Dashboard personalizado

## Requisitos
- PHP >= 8.1
- Laravel >= 10.x

## Instalación
```bash
composer install
php artisan migrate
```

## Autor y Propietario
**Néstor Serrano**  
Desarrollador Full Stack  
GitHub: [@nestorserrano](https://github.com/nestorserrano)

## Licencia
Propietario - © 2026 Néstor Serrano. Todos los derechos reservados.
