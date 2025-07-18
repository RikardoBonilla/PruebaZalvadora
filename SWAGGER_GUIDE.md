# Guía de Documentación Swagger

## Acceso a la Documentación

### Swagger UI Interactivo
Accede a la documentación interactiva en: **http://localhost:8080/api/documentation**

### Especificación JSON
La especificación completa está disponible en: **http://localhost:8080/docs/api-docs.json**

## Características de la Documentación

### ✅ **Completamente Implementado**
- **Esquemas de datos** para todas las entidades (Plan, Error responses)
- **Validaciones** documentadas con ejemplos
- **Autenticación** con Bearer token
- **Respuestas de error** estándar (401, 403, 404, 422)
- **Ejemplos** de request y response para cada endpoint

### 📱 **Endpoints Documentados**
- `GET /api/v1/plans` - Listar todos los planes
- `POST /api/v1/plans` - Crear un nuevo plan
- `GET /api/v1/plans/{id}` - Obtener plan específico
- `PUT /api/v1/plans/{id}` - Actualizar plan existente
- `DELETE /api/v1/plans/{id}` - Eliminar plan

## Cómo Usar la Documentación

### 1. **Explorar Endpoints**
- Cada endpoint tiene descripción detallada
- Parámetros requeridos y opcionales claramente marcados
- Tipos de datos y validaciones especificadas

### 2. **Probar la API**
- Hacer clic en "Try it out" en cualquier endpoint
- Rellenar los parámetros requeridos
- Ejecutar la petición directamente desde la UI

### 3. **Autenticación**
- Hacer clic en "Authorize" en la parte superior
- Introducir el Bearer token: `Bearer your-token-here`
- Todas las peticiones subsecuentes incluirán la autenticación

## Esquemas Principales

### **Plan Schema**
```json
{
  "id": "550e8400-e29b-41d4-a716-446655440000",
  "name": "Plan Básico",
  "monthly_price": {
    "amount": 2999,
    "currency": "USD"
  },
  "user_limit": 10,
  "features": [
    "Dashboard básico",
    "Soporte por email",
    "API access"
  ],
  "created_at": "2024-01-15 10:30:00",
  "updated_at": "2024-01-16 15:45:00"
}
```

### **Create Plan Request**
```json
{
  "name": "Plan Premium",
  "monthly_price": 4999,
  "currency": "USD",
  "user_limit": 25,
  "features": [
    "Dashboard avanzado",
    "Soporte prioritario",
    "API ilimitado",
    "Reportes personalizados"
  ]
}
```

## Ejemplo de Uso

### 1. **Crear un Plan**
```bash
curl -X POST http://localhost:8080/api/v1/plans \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer your-token" \
  -d '{
    "name": "Plan Startup",
    "monthly_price": 1999,
    "currency": "USD", 
    "user_limit": 5,
    "features": ["Dashboard básico", "API limitado"]
  }'
```

### 2. **Listar Planes**
```bash
curl -X GET http://localhost:8080/api/v1/plans \
  -H "Authorization: Bearer your-token"
```

### 3. **Obtener Plan Específico**
```bash
curl -X GET http://localhost:8080/api/v1/plans/{plan-id} \
  -H "Authorization: Bearer your-token"
```

## Regenerar Documentación

Si modificas las anotaciones OpenAPI, regenera la documentación:

```bash
docker exec zalvadora_app php artisan l5-swagger:generate
```

## Notas Técnicas

### **Ubicación de Anotaciones**
- Esquemas principales: `app/Http/Controllers/SwaggerController.php`
- Endpoints: `app/Presentation/Controllers/Api/V1/PlanController.php`

### **Configuración**
- Archivo de configuración: `config/l5-swagger.php`
- Título personalizado: "Zalvadora SaaS Platform API"
- Servidor por defecto: `http://localhost:8080/api`

### **Seguridad**
- Esquema de autenticación: Bearer JWT
- Todos los endpoints requieren autenticación
- Autorización basada en roles (admin para CRUD de planes)