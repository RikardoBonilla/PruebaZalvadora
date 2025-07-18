# Zalvadora - API RESTful para Gesti�n de Planes

## =� Descripci�n del Proyecto

Zalvadora es una aplicaci�n Laravel 12 que implementa una API RESTful para la gesti�n de planes de suscripci�n. Utiliza arquitectura Domain-Driven Design (DDD) y est� completamente dockerizada para facilitar el desarrollo y despliegue.

## <� Arquitectura del Sistema

### Arquitectura DDD (Domain-Driven Design)

El proyecto est� estructurado siguiendo los principios de DDD con las siguientes capas:

#### 1. **Capa de Dominio** (`app/Domain/`)
- **Entidades**: Plan, Company, User con l�gica de negocio
- **Objetos de Valor**: Money, UserLimit, PlanName, Email, etc.
- **Eventos**: Eventos de dominio como PlanCreated
- **Interfaces de Repositorio**: Contratos para persistencia de datos

#### 2. **Capa de Aplicaci�n** (`app/Application/`)
- **DTOs**: Data Transfer Objects para l�mites de API
- **Casos de Uso**: Operaciones de negocio (CreatePlan, UpdatePlan, etc.)
- **Servicios**: Servicios espec�ficos de aplicaci�n

#### 3. **Capa de Infraestructura** (`app/Infrastructure/`)
- **Modelos**: Modelos Eloquent (PlanModel, CompanyModel, UserModel)
- **Repositorios**: Implementaciones concretas de repositorios
- **Eventos**: Infraestructura de manejo de eventos

#### 4. **Capa de Presentaci�n** (`app/Presentation/`)
- **Controladores**: Controladores API con inyecci�n de dependencias
- **Requests**: Clases de validaci�n de formularios
- **Resources**: Transformadores de respuesta API
- **Policies**: L�gica de autorizaci�n

## =3 Configuraci�n del Entorno Docker

### Servicios Incluidos

1. **Laravel App** (PHP 8.4 FPM Alpine)
2. **Nginx** (Servidor web)
3. **MySQL 8.0** (Base de datos)
4. **Vite** (Compilaci�n de assets frontend con TailwindCSS)

### Puertos de Configuraci�n

- **Aplicaci�n**: http://localhost:8080 (Nginx)
- **Base de datos**: localhost:3306 (MySQL)

## =� Instalaci�n y Configuraci�n

### Requisitos Previos

- Docker y Docker Compose instalados
- Git para clonar el repositorio

### Pasos de Instalaci�n

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

4. **Acceder al contenedor de la aplicaci�n**
   ```bash
   docker exec -it zalvadora_app sh
   ```

5. **Instalar dependencias PHP**
   ```bash
   cd /var/www/html
   composer install
   ```

6. **Generar clave de aplicaci�n**
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

### Configuraci�n de Base de Datos

Las variables de entorno para Docker:
- **Host**: `db` (nombre del servicio Docker)
- **Puerto**: 3306
- **Base de datos**: `zalvadora_db`
- **Usuario**: `root`
- **Contrase�a**: `root`

## = Flujo de Trabajo de la Aplicaci�n

### Arquitectura de Flujo de Datos

```
=� Cliente HTTP Request
    �
< Nginx (Puerto 8080)
    �
= Laravel Application
    �
<� Router (routes/api_v1.php)
    �
<� Controller (Presentation Layer)
    �
=' Use Case (Application Layer)
    �
<� Repository Interface (Domain Layer)
    �
=� Repository Implementation (Infrastructure Layer)
    �
=� Eloquent Model
    �
=, MySQL Database
```

### Flujo Detallado por Endpoint

#### 1. **GET /api/v1/plans** - Listar Planes

**Archivo de Entrada**: `routes/api_v1.php:15`
```php
Route::get('/plans', [PlanController::class, 'index']);
```

**Flujo de Archivos**:
1. **Router** � `routes/api_v1.php`
2. **Controller** � `app/Presentation/Controllers/PlanController.php:index()`
3. **Use Case** � `app/Application/UseCases/Plan/GetAllPlansUseCase.php`
4. **Repository** � `app/Infrastructure/Repositories/EloquentPlanRepository.php:findAll()`
5. **Model** � `app/Infrastructure/Models/PlanModel.php`
6. **Response** � `app/Presentation/Resources/PlanResource.php`

**Informaci�n Transferida**:
- **Entrada**: Request vac�o (solo headers de autenticaci�n)
- **Proceso**: Consulta a base de datos para obtener todos los planes
- **Salida**: JSON con lista de planes transformados

#### 2. **POST /api/v1/plans** - Crear Plan

**Flujo de Archivos**:
1. **Router** � `routes/api_v1.php:16`
2. **Validation** � `app/Presentation/Requests/CreatePlanRequest.php`
3. **Controller** � `app/Presentation/Controllers/PlanController.php:store()`
4. **DTO** � `app/Application/DTOs/PlanDto.php`
5. **Use Case** � `app/Application/UseCases/Plan/CreatePlanUseCase.php`
6. **Entity** � `app/Domain/Entities/Plan.php`
7. **Repository** � `app/Infrastructure/Repositories/EloquentPlanRepository.php:save()`
8. **Model** � `app/Infrastructure/Models/PlanModel.php`

**Informaci�n Transferida**:
- **Entrada**: JSON con datos del plan (name, monthly_price_amount, monthly_price_currency, user_limit, features)
- **Validaci�n**: Reglas de negocio y formato
- **Proceso**: Creaci�n de entidad de dominio y persistencia
- **Salida**: JSON con plan creado y c�digo 201

#### 3. **GET /api/v1/plans/{id}** - Mostrar Plan

**Flujo de Archivos**:
1. **Router** � `routes/api_v1.php:17`
2. **Controller** � `app/Presentation/Controllers/PlanController.php:show()`
3. **Use Case** � `app/Application/UseCases/Plan/GetPlanByIdUseCase.php`
4. **Repository** � `app/Infrastructure/Repositories/EloquentPlanRepository.php:findById()`
5. **Model** � `app/Infrastructure/Models/PlanModel.php`

**Informaci�n Transferida**:
- **Entrada**: UUID del plan en la URL
- **Proceso**: B�squeda por ID en base de datos
- **Salida**: JSON con datos completos del plan o error 404

#### 4. **PUT /api/v1/plans/{id}** - Actualizar Plan

**Flujo de Archivos**:
1. **Router** � `routes/api_v1.php:18`
2. **Validation** � `app/Presentation/Requests/UpdatePlanRequest.php`
3. **Controller** � `app/Presentation/Controllers/PlanController.php:update()`
4. **DTO** � `app/Application/DTOs/PlanDto.php`
5. **Use Case** � `app/Application/UseCases/Plan/UpdatePlanUseCase.php`
6. **Repository** � `app/Infrastructure/Repositories/EloquentPlanRepository.php:update()`

**Informaci�n Transferida**:
- **Entrada**: UUID + JSON con datos actualizados
- **Proceso**: Validaci�n, b�squeda, actualizaci�n de entidad
- **Salida**: JSON con plan actualizado

#### 5. **DELETE /api/v1/plans/{id}** - Eliminar Plan

**Flujo de Archivos**:
1. **Router** � `routes/api_v1.php:19`
2. **Controller** � `app/Presentation/Controllers/PlanController.php:destroy()`
3. **Use Case** � `app/Application/UseCases/Plan/DeletePlanUseCase.php`
4. **Repository** � `app/Infrastructure/Repositories/EloquentPlanRepository.php:delete()`

**Informaci�n Transferida**:
- **Entrada**: UUID del plan
- **Proceso**: Verificaci�n de existencia y eliminaci�n
- **Salida**: Respuesta vac�a con c�digo 204

## =� Sistema de Autenticaci�n

### Laravel Sanctum

La aplicaci�n utiliza Laravel Sanctum para autenticaci�n basada en tokens:

1. **Login** � `POST /api/v1/login`
   - **Entrada**: email, password
   - **Proceso**: Validaci�n de credenciales
   - **Salida**: Token de acceso

2. **Protecci�n de Rutas**:
   - Middleware `auth:sanctum` en rutas protegidas
   - Header requerido: `Authorization: Bearer {token}`

## =� Esquema de Base de Datos

### Tabla: plans
```sql
- id (UUID) - Clave primaria
- name (VARCHAR) - Nombre del plan
- monthly_price_amount (INTEGER) - Precio en centavos
- monthly_price_currency (VARCHAR) - C�digo de moneda
- user_limit (INTEGER) - L�mite m�ximo de usuarios
- features (JSON) - Array de caracter�sticas
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

### Tabla: companies
```sql
- id (UUID) - Clave primaria
- name (VARCHAR) - Nombre de la empresa
- email (VARCHAR) - Email �nico de la empresa
- plan_id (UUID) - Clave for�nea a plans
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

### Tabla: users
```sql
- id (UUID) - Clave primaria
- name (VARCHAR) - Nombre del usuario
- email (VARCHAR) - Email �nico del usuario
- company_id (UUID) - Clave for�nea a companies
- role (ENUM) - Rol del usuario (admin/user)
- password (VARCHAR) - Contrase�a encriptada
- created_at (TIMESTAMP)
- updated_at (TIMESTAMP)
```

## =� Documentaci�n API

### Swagger/OpenAPI

- **URL**: http://localhost:8080/api/documentation
- **JSON Spec**: http://localhost:8080/docs/api-docs.json
- **Caracter�sticas**:
  - Documentaci�n completa con OpenAPI 3.0
  - Esquemas de request/response
  - Documentaci�n de autenticaci�n
  - Pruebas interactivas
  - Ejemplos de requests y responses

### Endpoints Principales

| M�todo | Endpoint | Descripci�n | Autenticaci�n |
|--------|----------|-------------|---------------|
| GET | `/api/v1/plans` | Listar planes | Requerida (Admin) |
| POST | `/api/v1/plans` | Crear plan | Requerida (Admin) |
| GET | `/api/v1/plans/{id}` | Mostrar plan | Opcional |
| PUT | `/api/v1/plans/{id}` | Actualizar plan | Requerida (Admin) |
| DELETE | `/api/v1/plans/{id}` | Eliminar plan | Requerida (Admin) |
| POST | `/api/v1/login` | Iniciar sesi�n | No |

## >� Testing

### Estructura de Pruebas

#### Pruebas Unitarias (`tests/Unit/`)
- Value Objects de dominio (Money, UserLimit, PlanName, etc.)
- Entidades de dominio (Plan, Company, User)
- Casos de uso y l�gica de negocio

#### Pruebas de Funcionalidad (`tests/Feature/`)
- Funcionalidad de endpoints API
- Autenticaci�n y autorizaci�n
- Integraci�n con base de datos

### Comandos de Testing

```bash
# Ejecutar todas las pruebas
docker exec zalvadora_app php artisan test

# Ejecutar pruebas espec�ficas
docker exec zalvadora_app php artisan test --filter=PlanTest

# Ejecutar con cobertura
docker exec zalvadora_app php artisan test --coverage
```

## =� Comandos de Desarrollo

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

# Formateo de c�digo
./vendor/bin/pint

# Migraciones
php artisan migrate

# Limpiar cach� de configuraci�n
php artisan config:clear

# Generar documentaci�n Swagger
php artisan l5-swagger:generate
```

### Comandos Frontend (dentro del directorio src/)

```bash
# Build de desarrollo con hot reload
npm run dev

# Build de producci�n
npm run build

# Watch para cambios
npm run watch
```

## =� Estructura de Archivos Clave

```
PruebaZalvadora/
   docker/                          # Configuraciones Docker
      app/Dockerfile               # Contenedor PHP-FPM
      nginx/default.conf           # Configuraci�n Nginx
   src/                             # Aplicaci�n Laravel
      app/
         Domain/                  # Capa de Dominio
            Entities/
            ValueObjects/
            Repositories/
         Application/             # Capa de Aplicaci�n
            DTOs/
            UseCases/
         Infrastructure/          # Capa de Infraestructura
            Models/
            Repositories/
         Presentation/            # Capa de Presentaci�n
             Controllers/
             Requests/
             Resources/
      routes/api_v1.php           # Rutas API v1
      database/migrations/         # Migraciones de BD
      tests/                       # Pruebas unitarias y de funcionalidad
   docker-compose.yml              # Configuraci�n Docker Compose
   README.md                       # Esta documentaci�n
```

## =' Dependencias Principales

### PHP (Composer)
- **Laravel Framework 12.x** - Framework principal
- **Laravel Sanctum** - Autenticaci�n API
- **L5 Swagger** - Documentaci�n API
- **Laravel Tinker** - REPL para debugging
- **PHPUnit** - Framework de testing
- **Laravel Pint** - Formateo de c�digo
- **Ramsey UUID** - Generaci�n de UUIDs

### Node.js (NPM)
- **Vite** - Compilaci�n de assets
- **TailwindCSS** - Framework de estilos
- **Laravel Vite Plugin** - Integraci�n con Laravel
- **Concurrently** - Ejecuci�n de m�ltiples comandos

## =� Soluci�n de Problemas Comunes

### Error de Conexi�n a Base de Datos
```bash
# Verificar que el contenedor de BD est� ejecut�ndose
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

### Cache de Configuraci�n
```bash
# Limpiar todos los caches
docker exec zalvadora_app php artisan config:clear
docker exec zalvadora_app php artisan cache:clear
docker exec zalvadora_app php artisan route:clear
docker exec zalvadora_app php artisan view:clear
```

## =� Rendimiento y Optimizaci�n

### Recomendaciones para Producci�n

1. **Configurar cach�**:
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

2. **Optimizar Composer**:
   ```bash
   composer install --optimize-autoloader --no-dev
   ```

3. **Compilar assets para producci�n**:
   ```bash
   npm run build
   ```

## 📋 Guía para Crear un CRUD Completo para Nueva Tabla

### Pasos para Implementar un Endpoint y CRUD Completo

Esta guía te llevará paso a paso para crear un CRUD completo siguiendo la arquitectura DDD implementada en el proyecto.

#### Ejemplo: Crear CRUD para tabla "Categorias"

### 1. 🏗️ Creación de Migración y Modelo

```bash
# Crear migración
docker exec zalvadora_app php artisan make:migration create_categorias_table

# Editar el archivo de migración en database/migrations/
```

**Estructura de migración ejemplo:**
```php
Schema::create('categorias', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('name', 100);
    $table->text('description')->nullable();
    $table->boolean('is_active')->default(true);
    $table->timestamps();
});
```

### 2. 📦 Capa de Dominio (Domain Layer)

#### 2.1 Crear Value Objects (`app/Domain/ValueObjects/`)
```php
// app/Domain/ValueObjects/CategoriaName.php
final readonly class CategoriaName
{
    public function __construct(public string $value)
    {
        if (strlen($value) > 100) {
            throw new InvalidArgumentException('Categoria name cannot exceed 100 characters');
        }
    }
}
```

#### 2.2 Crear Entidad de Dominio (`app/Domain/Entities/`)
```php
// app/Domain/Entities/Categoria.php
final class Categoria
{
    public function __construct(
        public readonly CategoriaId $id,
        public CategoriaName $name,
        public ?string $description,
        public bool $isActive,
        public readonly \DateTimeImmutable $createdAt
    ) {}
}
```

#### 2.3 Crear Interface de Repositorio (`app/Domain/Repositories/`)
```php
// app/Domain/Repositories/CategoriaRepositoryInterface.php
interface CategoriaRepositoryInterface
{
    public function findAll(): array;
    public function findById(CategoriaId $id): ?Categoria;
    public function save(Categoria $categoria): Categoria;
    public function update(Categoria $categoria): Categoria;
    public function delete(CategoriaId $id): void;
}
```

### 3. 🔧 Capa de Aplicación (Application Layer)

#### 3.1 Crear DTOs (`app/Application/DTOs/`)
```php
// app/Application/DTOs/CategoriaDto.php
final readonly class CategoriaDto
{
    public function __construct(
        public string $name,
        public ?string $description,
        public bool $isActive = true
    ) {}
}
```

#### 3.2 Crear Casos de Uso (`app/Application/UseCases/Categoria/`)
```php
// app/Application/UseCases/Categoria/CreateCategoriaUseCase.php
// app/Application/UseCases/Categoria/ListCategoriasUseCase.php
// app/Application/UseCases/Categoria/GetCategoriaByIdUseCase.php
// app/Application/UseCases/Categoria/UpdateCategoriaUseCase.php
// app/Application/UseCases/Categoria/DeleteCategoriaUseCase.php
```

### 4. 🏗️ Capa de Infraestructura (Infrastructure Layer)

#### 4.1 Crear Modelo Eloquent (`app/Infrastructure/Models/`)
```php
// app/Infrastructure/Models/CategoriaModel.php
class CategoriaModel extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'categorias';
    protected $fillable = ['name', 'description', 'is_active'];
    protected $casts = ['is_active' => 'boolean'];
}
```

#### 4.2 Implementar Repositorio (`app/Infrastructure/Repositories/`)
```php
// app/Infrastructure/Repositories/EloquentCategoriaRepository.php
final readonly class EloquentCategoriaRepository implements CategoriaRepositoryInterface
{
    public function __construct(private CategoriaModel $model) {}
    
    public function findAll(): array { /* implementación */ }
    public function findById(CategoriaId $id): ?Categoria { /* implementación */ }
    // ... resto de métodos
}
```

### 5. 🌐 Capa de Presentación (Presentation Layer)

#### 5.1 Crear Requests de Validación (`app/Presentation/Requests/`)
```php
// app/Presentation/Requests/CreateCategoriaRequest.php
// app/Presentation/Requests/UpdateCategoriaRequest.php
class CreateCategoriaRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'is_active' => 'boolean'
        ];
    }
}
```

#### 5.2 Crear Resource de Respuesta (`app/Presentation/Resources/`)
```php
// app/Presentation/Resources/CategoriaResource.php
class CategoriaResource extends JsonResource
{
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->isActive,
            'created_at' => $this->createdAt,
            'updated_at' => $this->updatedAt
        ];
    }
}
```

#### 5.3 Crear Controlador (`app/Presentation/Controllers/Api/V1/`)
```php
// app/Presentation/Controllers/Api/V1/CategoriaController.php
class CategoriaController extends Controller
{
    public function __construct(
        private readonly CreateCategoriaUseCase $createUseCase,
        private readonly ListCategoriasUseCase $listUseCase,
        // ... otros use cases
    ) {}

    public function index() { /* implementación */ }
    public function store(CreateCategoriaRequest $request) { /* implementación */ }
    public function show(string $id) { /* implementación */ }
    public function update(UpdateCategoriaRequest $request, string $id) { /* implementación */ }
    public function destroy(string $id) { /* implementación */ }
}
```

### 6. 🛣️ Configuración de Rutas

#### 6.1 Añadir Rutas API (`routes/api_v1.php`)
```php
// Rutas públicas
Route::get('/categorias', [CategoriaController::class, 'index']);
Route::get('/categorias/{id}', [CategoriaController::class, 'show']);

// Rutas protegidas (requieren autenticación)
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/categorias', [CategoriaController::class, 'store']);
    Route::put('/categorias/{id}', [CategoriaController::class, 'update']);
    Route::delete('/categorias/{id}', [CategoriaController::class, 'destroy']);
});
```

### 7. 📚 Documentación Swagger

#### 7.1 Añadir Esquemas al SwaggerController
```php
// En app/Http/Controllers/SwaggerController.php
#[OA\Tag(
    name: 'Categorias',
    description: 'Gestión de categorías'
)]

#[OA\Schema(
    schema: 'Categoria',
    title: 'Categoria',
    description: 'Categoría del sistema',
    // ... propiedades
)]
```

#### 7.2 Documentar Endpoints en el Controlador
```php
#[OA\Get(
    path: '/api/v1/categorias',
    tags: ['Categorias'],
    summary: 'Listar categorías',
    // ... documentación completa
)]
```

### 8. 🧪 Crear Tests

#### 8.1 Tests Unitarios (`tests/Unit/`)
```php
// tests/Unit/Domain/ValueObjects/CategoriaNameTest.php
// tests/Unit/Domain/Entities/CategoriaTest.php
// tests/Unit/Application/UseCases/Categoria/CreateCategoriaUseCaseTest.php
```

#### 8.2 Tests de Feature (`tests/Feature/`)
```php
// tests/Feature/Api/CategoriaControllerTest.php
class CategoriaControllerTest extends TestCase
{
    public function test_can_list_categorias() { /* implementación */ }
    public function test_can_create_categoria() { /* implementación */ }
    public function test_can_show_categoria() { /* implementación */ }
    public function test_can_update_categoria() { /* implementación */ }
    public function test_can_delete_categoria() { /* implementación */ }
}
```

### 9. 🔧 Configuración de Dependencias

#### 9.1 Registrar en Service Provider (`app/Providers/AppServiceProvider.php`)
```php
public function register(): void
{
    $this->app->bind(
        CategoriaRepositoryInterface::class,
        EloquentCategoriaRepository::class
    );
}
```

### 10. ✅ Verificación Final

#### Ejecutar Comandos de Verificación:
```bash
# Ejecutar migraciones
docker exec zalvadora_app php artisan migrate

# Ejecutar tests
docker exec zalvadora_app php artisan test

# Formatear código
docker exec zalvadora_app ./vendor/bin/pint

# Generar documentación Swagger
docker exec zalvadora_app php artisan l5-swagger:generate

# Verificar rutas
docker exec zalvadora_app php artisan route:list --path=categorias
```

### 📋 Checklist de Implementación

- [ ] ✅ Migración creada y ejecutada
- [ ] ✅ Value Objects implementados
- [ ] ✅ Entidad de dominio creada
- [ ] ✅ Interface de repositorio definida
- [ ] ✅ DTOs implementados
- [ ] ✅ Casos de uso creados (CRUD completo)
- [ ] ✅ Modelo Eloquent implementado
- [ ] ✅ Repositorio concreto implementado
- [ ] ✅ Requests de validación creados
- [ ] ✅ Resource de respuesta implementado
- [ ] ✅ Controlador con todos los métodos CRUD
- [ ] ✅ Rutas registradas en api_v1.php
- [ ] ✅ Documentación Swagger completa
- [ ] ✅ Tests unitarios implementados
- [ ] ✅ Tests de feature implementados
- [ ] ✅ Dependencias registradas en Service Provider
- [ ] ✅ Políticas de autorización (si aplica)
- [ ] ✅ Seeders para datos iniciales (si aplica)

### 🚀 Resultado Final

Al seguir estos pasos tendrás:
- ✅ API RESTful completa con 5 endpoints (GET, POST, GET/{id}, PUT/{id}, DELETE/{id})
- ✅ Arquitectura DDD respetada
- ✅ Validación de datos
- ✅ Documentación Swagger interactiva
- ✅ Tests automatizados
- ✅ Autorización implementada
- ✅ Códigos de respuesta HTTP apropiados
- ✅ Manejo de errores estándar

## > Contribuci�n

### Flujo de Trabajo

1. Crear rama feature desde main
2. Implementar cambios siguiendo arquitectura DDD
3. A�adir pruebas unitarias y de funcionalidad
4. Ejecutar formateo con Laravel Pint
5. Verificar que todas las pruebas pasen
6. Crear Pull Request

### Est�ndares de C�digo

- Seguir PSR-12 para PHP
- Usar Laravel Pint para formateo autom�tico
- Documentar m�todos p�blicos con PHPDoc
- Mantener cobertura de pruebas > 80%

## =� Licencia

Este proyecto es parte de una prueba t�cnica para Zalvadora.

---

**Desarrollado con d usando Laravel 12, Docker, y arquitectura DDD**