# Guía de Testing de la API

## 🌐 URLs Principales

- **Swagger UI**: http://localhost:8080/api/documentation
- **API Base**: http://localhost:8080/api/v1
- **JSON Spec**: http://localhost:8080/docs/api-docs.json

## 🔓 Endpoints Públicos (Sin Autenticación)

### ✅ Listar todos los planes
```bash
curl -X GET http://localhost:8080/api/v1/plans \
  -H "Accept: application/json"
```

### ✅ Obtener plan específico
```bash
curl -X GET http://localhost:8080/api/v1/plans/{plan-id} \
  -H "Accept: application/json"
```

## 🔐 Obtener Token de Acceso

### 🎯 Endpoint de Login
```bash
curl -X POST http://localhost:8080/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -d '{
    "email": "admin@example.com",
    "password": "password",
    "device_name": "Mi dispositivo"
  }'
```

**Respuesta esperada:**
```json
{
  "access_token": "1|abcdef123456789...",
  "token_type": "Bearer",
  "user": {
    "id": "uuid-del-usuario",
    "name": "Admin User",
    "email": "admin@example.com",
    "role": "admin"
  }
}
```

## 🔒 Endpoints Protegidos (Requieren Token)

### ✅ Crear un nuevo plan
```bash
curl -X POST http://localhost:8080/api/v1/plans \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -d '{
    "name": "Plan Startup",
    "monthly_price": 1999,
    "currency": "USD",
    "user_limit": 5,
    "features": ["Dashboard básico", "API limitado", "Soporte básico"]
  }'
```

### ✅ Actualizar un plan
```bash
curl -X PUT http://localhost:8080/api/v1/plans/{plan-id} \
  -H "Content-Type: application/json" \
  -H "Accept: application/json" \
  -H "Authorization: Bearer TU_TOKEN_AQUI" \
  -d '{
    "name": "Plan Startup Actualizado",
    "monthly_price": 2499,
    "currency": "USD",
    "user_limit": 8,
    "features": ["Dashboard mejorado", "API estándar", "Soporte prioritario"]
  }'
```

### ✅ Eliminar un plan
```bash
curl -X DELETE http://localhost:8080/api/v1/plans/{plan-id} \
  -H "Authorization: Bearer TU_TOKEN_AQUI"
```

### ✅ Cerrar sesión
```bash
curl -X POST http://localhost:8080/api/v1/auth/logout \
  -H "Authorization: Bearer TU_TOKEN_AQUI"
```

## 👥 Usuarios de Prueba Disponibles

### 🔑 Usuario Administrador
- **Email**: `admin@example.com`
- **Password**: `password`
- **Rol**: `admin`
- **Permisos**: Crear, actualizar y eliminar planes

### 👤 Usuario Normal
- **Email**: `user@example.com`
- **Password**: `password`
- **Rol**: `user`
- **Permisos**: Solo lectura (ver planes)

## 🧪 Workflow de Prueba Completo

### 1. **Obtener Token**
```bash
TOKEN=$(curl -s -X POST http://localhost:8080/api/v1/auth/login \
  -H "Content-Type: application/json" \
  -d '{"email":"admin@example.com","password":"password"}' \
  | jq -r '.access_token')
```

### 2. **Listar Planes (Sin Auth)**
```bash
curl -X GET http://localhost:8080/api/v1/plans \
  -H "Accept: application/json"
```

### 3. **Crear Plan (Con Auth)**
```bash
curl -X POST http://localhost:8080/api/v1/plans \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer $TOKEN" \
  -d '{
    "name": "Plan de Prueba",
    "monthly_price": 3999,
    "currency": "USD",
    "user_limit": 15,
    "features": ["Feature 1", "Feature 2"]
  }'
```

### 4. **Verificar Plan Creado**
```bash
curl -X GET http://localhost:8080/api/v1/plans \
  -H "Accept: application/json"
```

## 📋 Pruebas en Swagger UI

### 1. **Acceder a Swagger**
   Abre: `http://localhost:8080/api/documentation`

### 2. **Probar Endpoints Públicos**
   - Ve a `GET /api/v1/plans`
   - Haz clic en "Try it out"
   - Haz clic en "Execute"
   - ✅ Debería mostrar la lista de planes

### 3. **Obtener Token**
   - Ve a `POST /api/v1/auth/login`
   - Haz clic en "Try it out"
   - Completa:
     ```json
     {
       "email": "admin@example.com",
       "password": "password"
     }
     ```
   - Haz clic en "Execute"
   - Copia el `access_token` de la respuesta

### 4. **Autorizar en Swagger**
   - Haz clic en el botón "Authorize" (🔒) en la parte superior
   - Introduce: `Bearer TU_TOKEN_COPIADO`
   - Haz clic en "Authorize"

### 5. **Probar Endpoints Protegidos**
   - Ve a `POST /api/v1/plans`
   - Haz clic en "Try it out"
   - Completa los datos del plan
   - Haz clic en "Execute"
   - ✅ Debería crear el plan exitosamente

## ⚠️ Respuestas de Error Comunes

### 401 Unauthorized
```json
{
  "message": "Unauthenticated."
}
```
**Solución**: Asegúrate de incluir el token de autorización.

### 403 Forbidden
```json
{
  "message": "This action is unauthorized."
}
```
**Solución**: Usa un usuario con rol `admin` para operaciones de escritura.

### 422 Validation Error
```json
{
  "message": "The given data was invalid.",
  "errors": {
    "name": ["The name field is required."]
  }
}
```
**Solución**: Verifica que todos los campos requeridos estén presentes y sean válidos.

## 🎯 URLs de Prueba Rápida

- **Ver planes**: http://localhost:8080/api/v1/plans
- **Login**: POST http://localhost:8080/api/v1/auth/login
- **Swagger**: http://localhost:8080/api/documentation

¡Todo está listo para probar la API! 🚀