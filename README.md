# Zalvadora - API RESTful para Gestión de Planes

## =Ë Descripción del Proyecto

Zalvadora es una aplicación Laravel 12 que implementa una API RESTful para la gestión de planes de suscripción. Utiliza arquitectura Domain-Driven Design (DDD) y está completamente dockerizada para facilitar el desarrollo y despliegue.

## <× Arquitectura del Sistema

### Arquitectura DDD (Domain-Driven Design)

El proyecto está estructurado siguiendo los principios de DDD con las siguientes capas:

#### 1. **Capa de Dominio** (`app/Domain/`)
- **Entidades**: Plan, Company, User con lógica de negocio
- **Objetos de Valor**: Money, UserLimit, PlanName, Email, etc.
- **Eventos**: Eventos de dominio como PlanCreated
- **Interfaces de Repositorio**: Contratos para persistencia de datos

#### 2. **Capa de Aplicación** (`app/Application/`)
- **DTOs**: Data Transfer Objects para límites de API
- **Casos de Uso**: Operaciones de negocio (CreatePlan, UpdatePlan, etc.)
- **Servicios**: Servicios específicos de aplicación

#### 3. **Capa de Infraestructura** (`app/Infrastructure/`)
- **Modelos**: Modelos Eloquent (PlanModel, CompanyModel, UserModel)
- **Repositorios**: Implementaciones concretas de repositorios
- **Eventos**: Infraestructura de manejo de eventos

#### 4. **Capa de Presentación** (`app/Presentation/`)
- **Controladores**: Controladores API con inyección de dependencias
- **Requests**: Clases de validación de formularios
- **Resources**: Transformadores de respuesta API
- **Policies**: Lógica de autorización

## =3 Configuración del Entorno Docker

### Servicios Incluidos

1. **Laravel App** (PHP 8.4 FPM Alpine)
2. **Nginx** (Servidor web)
3. **MySQL 8.0** (Base de datos)
4. **Vite** (Compilación de assets frontend con TailwindCSS)

### Puertos de Configuración

- **Aplicación**: http://localhost:8080 (Nginx)
- **Base de datos**: localhost:3306 (MySQL)

## =€ Instalación y Configuración

### Requisitos Previos

- Docker y Docker Compose instalados
- Git para clonar el repositorio

### Pasos de Instalación

1. **Clonar el repositorio**
   ```bash
   git clone <repository-url>
   cd PruebaZalvadora
   ```

2. **Configurar variables de entorno**
   ```bash
   cp src/.env.example src/.env
   ```

3. **Levantar el entorno Docker**
   ```bash
   docker-compose up -d
   ```

4. **Acceder al contenedor de la aplicación**
   ```bash
   docker exec -it zalvadora_app sh
   ```

5. **Instalar dependencias PHP**
   ```bash
   cd /var/www/html
   composer install
   ```

6. **Generar clave de aplicación**
   ```bash
   php artisan key:generate
   ```

7. **Ejecutar migraciones**
   ```bash
   php artisan migrate
   ```

8. **Instalar dependencias Node.js**
   ```bash
   npm install
   ```

9. **Compilar assets**
   ```bash
   npm run dev
   ```

### Configuración de Base de Datos

Las variables de entorno para Docker:
- **Host**: `db` (nombre del servicio Docker)
- **Puerto**: 3306
- **Base de datos**: `zalvadora_db`
- **Usuario**: `root`
- **Contraseña**: `root`

## = Flujo de Trabajo de la Aplicación

### Arquitectura de Flujo de Datos

```
=ñ Cliente HTTP Request
    “
< Nginx (Puerto 8080)
    “
= Laravel Application
    “
<¯ Router (routes/api_v1.php)
    “
<® Controller (Presentation Layer)
    “
=' Use Case (Application Layer)
    “
<Û Repository Interface (Domain Layer)
    “
=¾ Repository Implementation (Infrastructure Layer)
    “
=Ä Eloquent Model
    “
=, MySQL Database
```

### Flujo Detallado por Endpoint

#### 1. **GET /api/v1/plans** - Listar Planes

**Archivo de Entrada**: `routes/api_v1.php:15`
```php
Route::get('/plans', [PlanController::class, 'index']);
```

**Flujo de Archivos**:
1. **Router** ’ `routes/api_v1.php`
2. **Controller** ’ `app/Presentation/Controllers/PlanController.php:index()`
3. **Use Case** ’ `app/Application/UseCases/Plan/GetAllPlansUseCase.php`
4. **Repository** ’ `app/Infrastructure/Repositories/EloquentPlanRepository.php:findAll()`
5. **Model** ’ `app/Infrastructure/Models/PlanModel.php`
6. **Response** ’ `app/Presentation/Resources/PlanResource.php`

**Información Transferida**:
- **Entrada**: Request vacío (solo headers de autenticación)
- **Proceso**: Consulta a base de datos para obtener todos los planes
- **Salida**: JSON con lista de planes transformados

#### 2. **POST /api/v1/plans** - Crear Plan

**Flujo de Archivos**:
1. **Router** ’ `routes/api_v1.php:16`
2. **Validation** ’ `app/Presentation/Requests/CreatePlanRequest.php`
3. **Controller** ’ `app/Presentation/Controllers/PlanController.php:store()`
4. **DTO** ’ `app/Application/DTOs/PlanDto.php`
5. **Use Case** ’ `app/Application/UseCases/Plan/CreatePlanUseCase.php`
6. **Entity** ’ `app/Domain/Entities/Plan.php`
7. **Repository** ’ `app/Infrastructure/Repositories/EloquentPlanRepository.php:save()`
8. **Model** ’ `app/Infrastructure/Models/PlanModel.php`

**Información Transferida**:
- **Entrada**: JSON con datos del plan (name, monthly_price_amount, monthly_price_currency, user_limit, features)
- **Validación**: Reglas de negocio y formato
- **Proceso**: Creación de entidad de dominio y persistencia
- **Salida**: JSON con plan creado y código 201

#### 3. **GET /api/v1/plans/{id}** - Mostrar Plan

**Flujo de Archivos**:
1. **Router** ’ `routes/api_v1.php:17`
2. **Controller** ’ `app/Presentation/Controllers/PlanController.php:show()`
3. **Use Case** ’ `app/Application/UseCases/Plan/GetPlanByIdUseCase.php`
4. **Repository** ’ `app/Infrastructure/Repositories/EloquentPlanRepository.php:findById()`
5. **Model** ’ `app/Infrastructure/Models/PlanModel.php`

**Información Transferida**:
- **Entrada**: UUID del plan en la URL
- **Proceso**: Búsqueda por ID en base de datos
- **Salida**: JSON con datos completos del plan o error 404

#### 4. **PUT /api/v1/plans/{id}** - Actualizar Plan

**Flujo de Archivos**:
1. **Router** ’ `routes/api_v1.php:18`
2. **Validation** ’ `app/Presentation/Requests/UpdatePlanRequest.php`
3. **Controller** ’ `app/Presentation/Controllers/PlanController.php:update()`
4. **DTO** ’ `app/Application/DTOs/PlanDto.php`
5. **Use Case** ’ `app/Application/UseCases/Plan/UpdatePlanUseCase.php`
6. **Repository** ’ `app/Infrastructure/Repositories/EloquentPlanRepository.php:update()`

**Información Transferida**:
- **Entrada**: UUID + JSON con datos actualizados
- **Proceso**: Validación, búsqueda, actualización de entidad
- **Salida**: JSON con plan actualizado

#### 5. **DELETE /api/v1/plans/{id}** - Eliminar Plan

**Flujo de Archivos**:
1. **Router** ’ `routes/api_v1.php:19`
2. **Controller** ’ `app/Presentation/Controllers/PlanController.php:destroy()`
3. **Use Case** ’ `app/Application/UseCases/Plan/DeletePlanUseCase.php`
4. **Repository** ’ `app/Infrastructure/Repositories/EloquentPlanRepository.php:delete()`

**Información Transferida**:
- **Entrada**: UUID del plan
- **Proceso**: Verificación de existencia y eliminación
- **Salida**: Respuesta vacía con código 204

## =á Sistema de Autenticación

### Laravel Sanctum

La aplicación utiliza Laravel Sanctum para autenticación basada en tokens:

1. **Login** ’ `POST /api/v1/login`
   - **Entrada**: email, password
   - **Proceso**: Validación de credenciales
   - **Salida**: Token de acceso

2. **Protección de Rutas**:
   - Middleware `auth:sanctum` en rutas protegidas
   - Header requerido: `Authorization: Bearer {token}`

## =Ê Esquema de Base de Datos

### Tabla: plans
```sql
- id (UUID) - Clave primaria
- name (VARCHAR) - Nombre del plan
- monthly_price_amount (INTEGER) - Precio en centavos
- monthly_price_currency (VARCHAR) - Código de moneda
- user_limit (INTEGER) - Límite máximo de usuarios
- features (JSON) - Array de características
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

### Tabla: companies
```sql
- id (UUID) - Clave primaria
- name (VARCHAR) - Nombre de la empresa
- email (VARCHAR) - Email único de la empresa
- plan_id (UUID) - Clave foránea a plans
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

### Tabla: users
```sql
- id (UUID) - Clave primaria
- name (VARCHAR) - Nombre del usuario
- email (VARCHAR) - Email único del usuario
- company_id (UUID) - Clave foránea a companies
- role (ENUM) - Rol del usuario (admin/user)
- password (VARCHAR) - Contraseña encriptada
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

## =Ú Documentación API

### Swagger/OpenAPI

- **URL**: http://localhost:8080/api/documentation
- **JSON Spec**: http://localhost:8080/docs/api-docs.json
- **Características**:
  - Documentación completa con OpenAPI 3.0
  - Esquemas de request/response
  - Documentación de autenticación
  - Pruebas interactivas
  - Ejemplos de requests y responses

### Endpoints Principales

| Método | Endpoint | Descripción | Autenticación |
|--------|----------|-------------|---------------|
| GET | `/api/v1/plans` | Listar planes | Requerida (Admin) |
| POST | `/api/v1/plans` | Crear plan | Requerida (Admin) |
| GET | `/api/v1/plans/{id}` | Mostrar plan | Opcional |
| PUT | `/api/v1/plans/{id}` | Actualizar plan | Requerida (Admin) |
| DELETE | `/api/v1/plans/{id}` | Eliminar plan | Requerida (Admin) |
| POST | `/api/v1/login` | Iniciar sesión | No |

## >ê Testing

### Estructura de Pruebas

#### Pruebas Unitarias (`tests/Unit/`)
- Value Objects de dominio (Money, UserLimit, PlanName, etc.)
- Entidades de dominio (Plan, Company, User)
- Casos de uso y lógica de negocio

#### Pruebas de Funcionalidad (`tests/Feature/`)
- Funcionalidad de endpoints API
- Autenticación y autorización
- Integración con base de datos

### Comandos de Testing

```bash
# Ejecutar todas las pruebas
docker exec zalvadora_app php artisan test

# Ejecutar pruebas específicas
docker exec zalvadora_app php artisan test --filter=PlanTest

# Ejecutar con cobertura
docker exec zalvadora_app php artisan test --coverage
```

## =à Comandos de Desarrollo

### Comandos Docker

```bash
# Iniciar entorno completo
docker-compose up -d

# Detener entorno
docker-compose down

# Acceder al contenedor de la app
docker exec -it zalvadora_app sh

# Ver logs
docker-compose logs -f app
```

### Comandos Laravel (dentro del directorio src/ o contenedor)

```bash
# Servidor de desarrollo (concurrente: server, queue, logs, vite)
composer dev

# Ejecutar pruebas
composer test
# o
php artisan test

# Formateo de código
./vendor/bin/pint

# Migraciones
php artisan migrate

# Limpiar caché de configuración
php artisan config:clear

# Generar documentación Swagger
php artisan l5-swagger:generate
```

### Comandos Frontend (dentro del directorio src/)

```bash
# Build de desarrollo con hot reload
npm run dev

# Build de producción
npm run build

# Watch para cambios
npm run watch
```

## =Á Estructura de Archivos Clave

```
PruebaZalvadora/
   docker/                          # Configuraciones Docker
      app/Dockerfile               # Contenedor PHP-FPM
      nginx/default.conf           # Configuración Nginx
   src/                             # Aplicación Laravel
      app/
         Domain/                  # Capa de Dominio
            Entities/
            ValueObjects/
            Repositories/
         Application/             # Capa de Aplicación
            DTOs/
            UseCases/
         Infrastructure/          # Capa de Infraestructura
            Models/
            Repositories/
         Presentation/            # Capa de Presentación
             Controllers/
             Requests/
             Resources/
      routes/api_v1.php           # Rutas API v1
      database/migrations/         # Migraciones de BD
      tests/                       # Pruebas unitarias y de funcionalidad
   docker-compose.yml              # Configuración Docker Compose
   README.md                       # Esta documentación
```

## =' Dependencias Principales

### PHP (Composer)
- **Laravel Framework 12.x** - Framework principal
- **Laravel Sanctum** - Autenticación API
- **L5 Swagger** - Documentación API
- **Laravel Tinker** - REPL para debugging
- **PHPUnit** - Framework de testing
- **Laravel Pint** - Formateo de código
- **Ramsey UUID** - Generación de UUIDs

### Node.js (NPM)
- **Vite** - Compilación de assets
- **TailwindCSS** - Framework de estilos
- **Laravel Vite Plugin** - Integración con Laravel
- **Concurrently** - Ejecución de múltiples comandos

## =¨ Solución de Problemas Comunes

### Error de Conexión a Base de Datos
```bash
# Verificar que el contenedor de BD esté ejecutándose
docker-compose ps

# Recrear contenedores
docker-compose down
docker-compose up -d --build
```

### Error de Permisos
```bash
# Desde el host
sudo chown -R $USER:$USER src/

# Dentro del contenedor
chown -R www-data:www-data /var/www/html/storage
chown -R www-data:www-data /var/www/html/bootstrap/cache
```

### Cache de Configuración
```bash
# Limpiar todos los caches
docker exec zalvadora_app php artisan config:clear
docker exec zalvadora_app php artisan cache:clear
docker exec zalvadora_app php artisan route:clear
docker exec zalvadora_app php artisan view:clear
```

## =È Rendimiento y Optimización

### Recomendaciones para Producción

1. **Configurar caché**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Optimizar Composer**:
   ```bash
   composer install --optimize-autoloader --no-dev
   ```

3. **Compilar assets para producción**:
   ```bash
   npm run build
   ```

## > Contribución

### Flujo de Trabajo

1. Crear rama feature desde main
2. Implementar cambios siguiendo arquitectura DDD
3. Añadir pruebas unitarias y de funcionalidad
4. Ejecutar formateo con Laravel Pint
5. Verificar que todas las pruebas pasen
6. Crear Pull Request

### Estándares de Código

- Seguir PSR-12 para PHP
- Usar Laravel Pint para formateo automático
- Documentar métodos públicos con PHPDoc
- Mantener cobertura de pruebas > 80%

## =Ä Licencia

Este proyecto es parte de una prueba técnica para Zalvadora.

---

**Desarrollado con d usando Laravel 12, Docker, y arquitectura DDD**