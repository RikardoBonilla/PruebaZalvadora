# Zalvadora - API RESTful para Gestion de Planes

## 📋 Descripcion del Proyecto

Zalvadora es una aplicacion Laravel 12 que implementa una API RESTful para la gestion de planes de suscripcion. Utiliza arquitectura Domain-Driven Design (DDD) y esta completamente dockerizada para facilitar el desarrollo y despliegue.

## 🏗️ Arquitectura del Sistema

### Arquitectura DDD (Domain-Driven Design)

El proyecto esta estructurado siguiendo los principios de DDD con las siguientes capas:

#### 1. **Capa de Dominio** (`app/Domain/`)
- **Entidades**: Plan, Company, User con logica de negocio
- **Objetos de Valor**: Money, UserLimit, PlanName, Email, etc.
- **Eventos**: Eventos de dominio como PlanCreated
- **Interfaces de Repositorio**: Contratos para persistencia de datos

#### 2. **Capa de Aplicacion** (`app/Application/`)
- **DTOs**: Data Transfer Objects para limites de API
- **Casos de Uso**: Operaciones de negocio (CreatePlan, UpdatePlan, etc.)
- **Servicios**: Servicios especificos de aplicacion

#### 3. **Capa de Infraestructura** (`app/Infrastructure/`)
- **Modelos**: Modelos Eloquent (PlanModel, CompanyModel, UserModel)
- **Repositorios**: Implementaciones concretas de repositorios
- **Eventos**: Infraestructura de manejo de eventos

#### 4. **Capa de Presentacion** (`app/Presentation/`)
- **Controladores**: Controladores API con inyeccion de dependencias
- **Requests**: Clases de validacion de formularios
- **Resources**: Transformadores de respuesta API
- **Policies**: Logica de autorizacion

## 🐳 Configuracion del Entorno Docker

### Servicios Incluidos

1. **Laravel App** (PHP 8.4 FPM Alpine)
2. **Nginx** (Servidor web)
3. **MySQL 8.0** (Base de datos)
4. **Vite** (Compilacion de assets frontend con TailwindCSS)

### Puertos de Configuracion

- **Aplicacion**: http://localhost:8080 (Nginx)
- **Base de datos**: localhost:3306 (MySQL)

## ⚙️ Instalacion y Configuracion

### Requisitos Previos

- Docker y Docker Compose instalados
- Git para clonar el repositorio

### Pasos de Instalacion

1. **Clonar el repositorio**
   ```bash
   git clone https://github.com/RikardoBonilla/PruebaZalvadora.git
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

4. **Acceder al contenedor de la aplicacion**
   ```bash
   docker exec -it zalvadora_app sh
   ```

5. **Instalar dependencias PHP**
   ```bash
   cd /var/www/html
   composer install
   ```

6. **Generar clave de aplicacion**
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

### Configuracion de Base de Datos

Las variables de entorno para Docker:
- **Host**: `db` (nombre del servicio Docker)
- **Puerto**: 3306
- **Base de datos**: `zalvadora_db`
- **Usuario**: `root`
- **Contraseña**: `root`

## 🔄 Flujo de Trabajo de la Aplicacion

### Arquitectura de Flujo de Datos

```
📥 Cliente HTTP Request
    ↓
🌐 Nginx (Puerto 8080)
    ↓
🚀 Laravel Application
    ↓
🛣️ Router (routes/api_v1.php)
    ↓
🎮 Controller (Presentation Layer)
    ↓
⚡ Use Case (Application Layer)
    ↓
📋 Repository Interface (Domain Layer)
    ↓
💾 Repository Implementation (Infrastructure Layer)
    ↓
📊 Eloquent Model
    ↓
🗃️ MySQL Database
```

### Flujo Detallado por Endpoint

#### 1. **GET /api/v1/plans** - Listar Planes

**Archivo de Entrada**: `routes/api_v1.php:15`
```php
Route::get('/plans', [PlanController::class, 'index']);
```

**Flujo de Archivos**:
1. **Router** → `routes/api_v1.php`
2. **Controller** → `app/Presentation/Controllers/PlanController.php:index()`
3. **Use Case** → `app/Application/UseCases/Plan/GetAllPlansUseCase.php`
4. **Repository** → `app/Infrastructure/Repositories/EloquentPlanRepository.php:findAll()`
5. **Model** → `app/Infrastructure/Models/PlanModel.php`
6. **Response** → `app/Presentation/Resources/PlanResource.php`

**Informacion Transferida**:
- **Entrada**: Request vacio (solo headers de autenticacion)
- **Proceso**: Consulta a base de datos para obtener todos los planes
- **Salida**: JSON con lista de planes transformados

#### 2. **POST /api/v1/plans** - Crear Plan

**Flujo de Archivos**:
1. **Router** → `routes/api_v1.php:16`
2. **Validation** → `app/Presentation/Requests/CreatePlanRequest.php`
3. **Controller** → `app/Presentation/Controllers/PlanController.php:store()`
4. **DTO** → `app/Application/DTOs/PlanDto.php`
5. **Use Case** → `app/Application/UseCases/Plan/CreatePlanUseCase.php`
6. **Entity** → `app/Domain/Entities/Plan.php`
7. **Repository** → `app/Infrastructure/Repositories/EloquentPlanRepository.php:save()`
8. **Model** → `app/Infrastructure/Models/PlanModel.php`

**Informacion Transferida**:
- **Entrada**: JSON con datos del plan (name, monthly_price_amount, monthly_price_currency, user_limit, features)
- **Validacion**: Reglas de negocio y formato
- **Proceso**: Creacion de entidad de dominio y persistencia
- **Salida**: JSON con plan creado y codigo 201

#### 3. **GET /api/v1/plans/{id}** - Mostrar Plan

**Flujo de Archivos**:
1. **Router** → `routes/api_v1.php:17`
2. **Controller** → `app/Presentation/Controllers/PlanController.php:show()`
3. **Use Case** → `app/Application/UseCases/Plan/GetPlanByIdUseCase.php`
4. **Repository** → `app/Infrastructure/Repositories/EloquentPlanRepository.php:findById()`
5. **Model** → `app/Infrastructure/Models/PlanModel.php`

**Informacion Transferida**:
- **Entrada**: UUID del plan en la URL
- **Proceso**: Busqueda por ID en base de datos
- **Salida**: JSON con datos completos del plan o error 404

#### 4. **PUT /api/v1/plans/{id}** - Actualizar Plan

**Flujo de Archivos**:
1. **Router** → `routes/api_v1.php:18`
2. **Validation** → `app/Presentation/Requests/UpdatePlanRequest.php`
3. **Controller** → `app/Presentation/Controllers/PlanController.php:update()`
4. **DTO** → `app/Application/DTOs/PlanDto.php`
5. **Use Case** → `app/Application/UseCases/Plan/UpdatePlanUseCase.php`
6. **Repository** → `app/Infrastructure/Repositories/EloquentPlanRepository.php:update()`

**Informacion Transferida**:
- **Entrada**: UUID + JSON con datos actualizados
- **Proceso**: Validacion, busqueda, actualizacion de entidad
- **Salida**: JSON con plan actualizado

#### 5. **DELETE /api/v1/plans/{id}** - Eliminar Plan

**Flujo de Archivos**:
1. **Router** → `routes/api_v1.php:19`
2. **Controller** → `app/Presentation/Controllers/PlanController.php:destroy()`
3. **Use Case** → `app/Application/UseCases/Plan/DeletePlanUseCase.php`
4. **Repository** → `app/Infrastructure/Repositories/EloquentPlanRepository.php:delete()`

**Informacion Transferida**:
- **Entrada**: UUID del plan
- **Proceso**: Verificacion de existencia y eliminacion
- **Salida**: Respuesta vacia con codigo 204

## 🔐 Sistema de Autenticacion

### Laravel Sanctum

La aplicacion utiliza Laravel Sanctum para autenticacion basada en tokens:

1. **Login** → `POST /api/v1/login`
   - **Entrada**: email, password
   - **Proceso**: Validacion de credenciales
   - **Salida**: Token de acceso

2. **Proteccion de Rutas**:
   - Middleware `auth:sanctum` en rutas protegidas
   - Header requerido: `Authorization: Bearer {token}`

## 🗄️ Esquema de Base de Datos

### Tabla: plans
```sql
- id (UUID) - Clave primaria
- name (VARCHAR) - Nombre del plan
- monthly_price_amount (INTEGER) - Precio en centavos
- monthly_price_currency (VARCHAR) - Codigo de moneda
- user_limit (INTEGER) - Limite maximo de usuarios
- features (JSON) - Array de caracteristicas
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

### Tabla: companies
```sql
- id (UUID) - Clave primaria
- name (VARCHAR) - Nombre de la empresa
- email (VARCHAR) - Email unico de la empresa
- plan_id (UUID) - Clave foranea a plans
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

### Tabla: users
```sql
- id (UUID) - Clave primaria
- name (VARCHAR) - Nombre del usuario
- email (VARCHAR) - Email unico del usuario
- company_id (UUID) - Clave foranea a companies
- role (ENUM) - Rol del usuario (admin/user)
- password (VARCHAR) - Contraseña encriptada
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

## 📚 Documentacion API

### Swagger/OpenAPI

- **URL**: http://localhost:8080/api/documentation
- **JSON Spec**: http://localhost:8080/docs/api-docs.json
- **Caracteristicas**:
  - Documentacion completa con OpenAPI 3.0
  - Esquemas de request/response
  - Documentacion de autenticacion
  - Pruebas interactivas
  - Ejemplos de requests y responses

### Endpoints Principales

| Metodo | Endpoint | Descripcion | Autenticacion |
|--------|----------|-------------|---------------|
| GET | `/api/v1/plans` | Listar planes | Requerida (Admin) |
| POST | `/api/v1/plans` | Crear plan | Requerida (Admin) |
| GET | `/api/v1/plans/{id}` | Mostrar plan | Opcional |
| PUT | `/api/v1/plans/{id}` | Actualizar plan | Requerida (Admin) |
| DELETE | `/api/v1/plans/{id}` | Eliminar plan | Requerida (Admin) |
| POST | `/api/v1/login` | Iniciar sesion | No |

## 🧪 Testing

### Estructura de Pruebas

#### Pruebas Unitarias (`tests/Unit/`)
- Value Objects de dominio (Money, UserLimit, PlanName, etc.)
- Entidades de dominio (Plan, Company, User)
- Casos de uso y logica de negocio

#### Pruebas de Funcionalidad (`tests/Feature/`)
- Funcionalidad de endpoints API
- Autenticacion y autorizacion
- Integracion con base de datos

### Comandos de Testing

```bash
# Ejecutar todas las pruebas
docker exec zalvadora_app php artisan test

# Ejecutar pruebas especificas
docker exec zalvadora_app php artisan test --filter=PlanTest

# Ejecutar con cobertura
docker exec zalvadora_app php artisan test --coverage
```

## 💻 Comandos de Desarrollo

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

# Formateo de codigo
./vendor/bin/pint

# Migraciones
php artisan migrate

# Limpiar cache de configuracion
php artisan config:clear

# Generar documentacion Swagger
php artisan l5-swagger:generate
```

### Comandos Frontend (dentro del directorio src/)

```bash
# Build de desarrollo con hot reload
npm run dev

# Build de produccion
npm run build

# Watch para cambios
npm run watch
```

## 📁 Estructura de Archivos Clave

```
PruebaZalvadora/
├── docker/                          # Configuraciones Docker
│   ├── app/Dockerfile               # Contenedor PHP-FPM
│   └── nginx/default.conf           # Configuracion Nginx
├── src/                             # Aplicacion Laravel
│   ├── app/
│   │   ├── Domain/                  # Capa de Dominio
│   │   │   ├── Entities/
│   │   │   ├── ValueObjects/
│   │   │   └── Repositories/
│   │   ├── Application/             # Capa de Aplicacion
│   │   │   ├── DTOs/
│   │   │   └── UseCases/
│   │   ├── Infrastructure/          # Capa de Infraestructura
│   │   │   ├── Models/
│   │   │   └── Repositories/
│   │   └── Presentation/            # Capa de Presentacion
│   │       ├── Controllers/
│   │       ├── Requests/
│   │       └── Resources/
│   ├── routes/api_v1.php           # Rutas API v1
│   ├── database/migrations/         # Migraciones de BD
│   └── tests/                       # Pruebas unitarias y de funcionalidad
└── docker-compose.yml              # Configuracion Docker Compose
└── README.md                       # Esta documentacion
```

## 📦 Dependencias Principales

### PHP (Composer)
- **Laravel Framework 12.x** - Framework principal
- **Laravel Sanctum** - Autenticacion API
- **L5 Swagger** - Documentacion API
- **Laravel Tinker** - REPL para debugging
- **PHPUnit** - Framework de testing
- **Laravel Pint** - Formateo de codigo
- **Ramsey UUID** - Generacion de UUIDs

### Node.js (NPM)
- **Vite** - Compilacion de assets
- **TailwindCSS** - Framework de estilos
- **Laravel Vite Plugin** - Integracion con Laravel
- **Concurrently** - Ejecucion de multiples comandos

## 🚨 Solucion de Problemas Comunes

### Error de Conexion a Base de Datos
```bash
# Verificar que el contenedor de BD este ejecutandose
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

### Cache de Configuracion
```bash
# Limpiar todos los caches
docker exec zalvadora_app php artisan config:clear
docker exec zalvadora_app php artisan cache:clear
docker exec zalvadora_app php artisan route:clear
docker exec zalvadora_app php artisan view:clear
```

## ⚡ Rendimiento y Optimizacion

### Recomendaciones para Produccion

1. **Configurar cache**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Optimizar Composer**:
   ```bash
   composer install --optimize-autoloader --no-dev
   ```

3. **Compilar assets para produccion**:
   ```bash
   npm run build
   ```


## 🤝 Contribucion

### Flujo de Trabajo

1. Crear rama feature desde main
2. Implementar cambios siguiendo arquitectura DDD
3. Añadir pruebas unitarias y de funcionalidad
4. Ejecutar formateo con Laravel Pint
5. Verificar que todas las pruebas pasen
6. Crear Pull Request

### Estandares de Codigo

- Seguir PSR-12 para PHP
- Usar Laravel Pint para formateo automatico
- Documentar metodos publicos con PHPDoc
- Mantener cobertura de pruebas > 80%

## 📄 Licencia

Este proyecto es parte de una prueba tecnica para Zalvadora.

---

**Desarrollado con ❤️ usando Laravel 12, Docker, y arquitectura DDD**