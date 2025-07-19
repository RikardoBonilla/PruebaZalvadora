<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: '1.0.0',
    description: 'API RESTful para plataforma SaaS multi-tenant con gestión de planes, empresas y usuarios. Implementada con DDD y arquitectura limpia.',
    title: 'Zalvadora SaaS Platform API',
)]
#[OA\Server(
    url: 'http://localhost:8080',
    description: 'Servidor de desarrollo'
)]
#[OA\SecurityScheme(
    securityScheme: 'bearerAuth',
    type: 'http',
    name: 'Authorization',
    in: 'header',
    scheme: 'bearer',
    bearerFormat: 'JWT'
)]
#[OA\Tag(
    name: 'Planes',
    description: 'Gestión de planes de suscripción'
)]
#[OA\Tag(
    name: 'Empresas',
    description: 'Gestión de empresas tenant'
)]
#[OA\Tag(
    name: 'Usuarios de Empresa',
    description: 'Gestión de usuarios dentro de empresas'
)]
#[OA\Schema(
    schema: 'Plan',
    title: 'Plan',
    description: 'Plan de suscripción con límites y características',
    type: 'object',
    required: ['id', 'name', 'monthly_price', 'user_limit', 'features', 'created_at'],
    properties: [
        new OA\Property(property: 'id', description: 'ID único del plan', type: 'string', format: 'uuid', example: '550e8400-e29b-41d4-a716-446655440000'),
        new OA\Property(property: 'name', description: 'Nombre del plan', type: 'string', maxLength: 100, example: 'Plan Básico'),
        new OA\Property(
            property: 'monthly_price',
            description: 'Precio mensual del plan',
            type: 'object',
            properties: [
                new OA\Property(property: 'amount', description: 'Precio en centavos', type: 'integer', example: 2999),
                new OA\Property(property: 'currency', description: 'Código de moneda', type: 'string', maxLength: 3, example: 'USD')
            ],
            required: ['amount', 'currency']
        ),
        new OA\Property(property: 'user_limit', description: 'Límite máximo de usuarios', type: 'integer', minimum: 1, example: 10),
        new OA\Property(
            property: 'features',
            description: 'Lista de características del plan',
            type: 'array',
            items: new OA\Items(type: 'string'),
            example: ['Dashboard básico', 'Soporte por email', 'API access']
        ),
        new OA\Property(property: 'created_at', description: 'Fecha de creación', type: 'string', format: 'date-time', example: '2024-01-15 10:30:00'),
        new OA\Property(property: 'updated_at', description: 'Fecha de última actualización', type: 'string', format: 'date-time', nullable: true, example: '2024-01-16 15:45:00')
    ]
)]
#[OA\Schema(
    schema: 'CreatePlanRequest',
    title: 'Crear Plan',
    description: 'Datos requeridos para crear un nuevo plan',
    type: 'object',
    required: ['name', 'monthly_price', 'currency', 'user_limit', 'features'],
    properties: [
        new OA\Property(property: 'name', description: 'Nombre del plan', type: 'string', maxLength: 100, example: 'Plan Premium'),
        new OA\Property(property: 'monthly_price', description: 'Precio mensual en centavos', type: 'integer', minimum: 0, example: 4999),
        new OA\Property(property: 'currency', description: 'Código de moneda (3 caracteres)', type: 'string', maxLength: 3, minLength: 3, example: 'USD'),
        new OA\Property(property: 'user_limit', description: 'Límite máximo de usuarios', type: 'integer', minimum: 1, example: 25),
        new OA\Property(
            property: 'features',
            description: 'Lista de características del plan',
            type: 'array',
            items: new OA\Items(type: 'string', maxLength: 255),
            example: ['Dashboard avanzado', 'Soporte prioritario', 'API ilimitado', 'Reportes personalizados']
        )
    ]
)]
#[OA\Schema(
    schema: 'UpdatePlanRequest',
    title: 'Actualizar Plan',
    description: 'Datos para actualizar un plan existente',
    type: 'object',
    required: ['name', 'monthly_price', 'currency', 'user_limit', 'features'],
    properties: [
        new OA\Property(property: 'name', description: 'Nombre del plan', type: 'string', maxLength: 100, example: 'Plan Premium'),
        new OA\Property(property: 'monthly_price', description: 'Precio mensual en centavos', type: 'integer', minimum: 0, example: 4999),
        new OA\Property(property: 'currency', description: 'Código de moneda (3 caracteres)', type: 'string', maxLength: 3, minLength: 3, example: 'USD'),
        new OA\Property(property: 'user_limit', description: 'Límite máximo de usuarios', type: 'integer', minimum: 1, example: 25),
        new OA\Property(
            property: 'features',
            description: 'Lista de características del plan',
            type: 'array',
            items: new OA\Items(type: 'string', maxLength: 255),
            example: ['Dashboard avanzado', 'Soporte prioritario', 'API ilimitado', 'Reportes personalizados']
        )
    ]
)]
#[OA\Schema(
    schema: 'PlanCollection',
    title: 'Colección de Planes',
    description: 'Lista de planes',
    type: 'object',
    required: ['data'],
    properties: [
        new OA\Property(
            property: 'data',
            description: 'Array de planes',
            type: 'array',
            items: new OA\Items(ref: '#/components/schemas/Plan')
        )
    ]
)]
#[OA\Schema(
    schema: 'Empresa',
    title: 'Empresa',
    description: 'Empresa tenant con plan asignado',
    type: 'object',
    required: ['id', 'nombre', 'email', 'plan_id', 'fecha_suscripcion', 'created_at'],
    properties: [
        new OA\Property(property: 'id', description: 'ID único de la empresa', type: 'string', format: 'uuid', example: '550e8400-e29b-41d4-a716-446655440000'),
        new OA\Property(property: 'nombre', description: 'Nombre de la empresa', type: 'string', maxLength: 255, example: 'Empresa Demo S.L.'),
        new OA\Property(property: 'email', description: 'Email de contacto de la empresa', type: 'string', format: 'email', example: 'contacto@empresademo.com'),
        new OA\Property(property: 'plan_id', description: 'ID del plan asignado', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'fecha_suscripcion', description: 'Fecha de suscripción al plan', type: 'string', format: 'date-time', example: '2024-01-15 10:30:00'),
        new OA\Property(property: 'created_at', description: 'Fecha de creación', type: 'string', format: 'date-time', example: '2024-01-15 10:30:00'),
        new OA\Property(property: 'updated_at', description: 'Fecha de última actualización', type: 'string', format: 'date-time', example: '2024-01-16 15:45:00')
    ]
)]
#[OA\Schema(
    schema: 'CreateEmpresaRequest',
    title: 'Crear Empresa',
    description: 'Datos requeridos para crear una nueva empresa',
    type: 'object',
    required: ['nombre', 'email', 'plan_id'],
    properties: [
        new OA\Property(property: 'nombre', description: 'Nombre de la empresa', type: 'string', maxLength: 255, example: 'Empresa Demo S.L.'),
        new OA\Property(property: 'email', description: 'Email de contacto de la empresa', type: 'string', format: 'email', example: 'contacto@empresademo.com'),
        new OA\Property(property: 'plan_id', description: 'ID del plan a asignar', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000')
    ]
)]
#[OA\Schema(
    schema: 'UpdateEmpresaRequest',
    title: 'Actualizar Empresa',
    description: 'Datos para actualizar una empresa existente',
    type: 'object',
    required: ['nombre', 'email'],
    properties: [
        new OA\Property(property: 'nombre', description: 'Nombre de la empresa', type: 'string', maxLength: 255, example: 'Empresa Demo S.L.'),
        new OA\Property(property: 'email', description: 'Email de contacto de la empresa', type: 'string', format: 'email', example: 'contacto@empresademo.com'),
        new OA\Property(property: 'plan_id', description: 'Nuevo plan a asignar (opcional)', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'motivo_cambio', description: 'Motivo del cambio de plan (opcional)', type: 'string', example: 'Upgrade por crecimiento del equipo')
    ]
)]
#[OA\Schema(
    schema: 'UsuarioEmpresa',
    title: 'Usuario de Empresa',
    description: 'Usuario perteneciente a una empresa',
    type: 'object',
    required: ['id', 'nombre', 'email', 'empresa_id', 'rol', 'created_at'],
    properties: [
        new OA\Property(property: 'id', description: 'ID único del usuario', type: 'string', format: 'uuid', example: '550e8400-e29b-41d4-a716-446655440000'),
        new OA\Property(property: 'nombre', description: 'Nombre del usuario', type: 'string', maxLength: 255, example: 'Juan Pérez'),
        new OA\Property(property: 'email', description: 'Email del usuario', type: 'string', format: 'email', example: 'juan.perez@empresademo.com'),
        new OA\Property(property: 'empresa_id', description: 'ID de la empresa a la que pertenece', type: 'string', format: 'uuid', example: '123e4567-e89b-12d3-a456-426614174000'),
        new OA\Property(property: 'rol', description: 'Rol del usuario en la empresa', type: 'string', enum: ['admin', 'usuario'], example: 'usuario'),
        new OA\Property(property: 'created_at', description: 'Fecha de creación', type: 'string', format: 'date-time', example: '2024-01-15 10:30:00'),
        new OA\Property(property: 'updated_at', description: 'Fecha de última actualización', type: 'string', format: 'date-time', example: '2024-01-16 15:45:00')
    ]
)]
#[OA\Schema(
    schema: 'CreateUsuarioEmpresaRequest',
    title: 'Crear Usuario de Empresa',
    description: 'Datos requeridos para crear un nuevo usuario de empresa',
    type: 'object',
    required: ['nombre', 'email', 'rol', 'password'],
    properties: [
        new OA\Property(property: 'nombre', description: 'Nombre del usuario', type: 'string', maxLength: 255, example: 'Juan Pérez'),
        new OA\Property(property: 'email', description: 'Email del usuario', type: 'string', format: 'email', example: 'juan.perez@empresademo.com'),
        new OA\Property(property: 'rol', description: 'Rol del usuario', type: 'string', enum: ['admin', 'usuario'], example: 'usuario'),
        new OA\Property(property: 'password', description: 'Contraseña del usuario', type: 'string', format: 'password', example: 'password123')
    ]
)]
#[OA\Schema(
    schema: 'UpdateUsuarioEmpresaRequest',
    title: 'Actualizar Usuario de Empresa',
    description: 'Datos para actualizar un usuario de empresa existente',
    type: 'object',
    required: ['nombre', 'email', 'rol'],
    properties: [
        new OA\Property(property: 'nombre', description: 'Nombre del usuario', type: 'string', maxLength: 255, example: 'Juan Pérez'),
        new OA\Property(property: 'email', description: 'Email del usuario', type: 'string', format: 'email', example: 'juan.perez@empresademo.com'),
        new OA\Property(property: 'rol', description: 'Rol del usuario', type: 'string', enum: ['admin', 'usuario'], example: 'usuario'),
        new OA\Property(property: 'password', description: 'Nueva contraseña (opcional)', type: 'string', format: 'password', example: 'newpassword123')
    ]
)]
#[OA\Schema(
    schema: 'Pagination',
    title: 'Paginación',
    description: 'Información de paginación para listas',
    type: 'object',
    required: ['current_page', 'per_page', 'total', 'last_page'],
    properties: [
        new OA\Property(property: 'current_page', description: 'Página actual', type: 'integer', example: 1),
        new OA\Property(property: 'per_page', description: 'Elementos por página', type: 'integer', example: 10),
        new OA\Property(property: 'total', description: 'Total de elementos', type: 'integer', example: 50),
        new OA\Property(property: 'last_page', description: 'Última página', type: 'integer', example: 5),
        new OA\Property(property: 'from', description: 'Primer elemento de la página', type: 'integer', example: 1),
        new OA\Property(property: 'to', description: 'Último elemento de la página', type: 'integer', example: 10)
    ]
)]
#[OA\Schema(
    schema: 'ErrorResponse',
    title: 'Error Response',
    description: 'Respuesta de error estándar',
    type: 'object',
    required: ['message'],
    properties: [
        new OA\Property(property: 'message', description: 'Mensaje de error', type: 'string', example: 'Plan not found'),
        new OA\Property(property: 'error', description: 'Detalles adicionales del error', type: 'string', example: 'The requested plan does not exist')
    ]
)]
#[OA\Schema(
    schema: 'ValidationErrorResponse',
    title: 'Validation Error Response',
    description: 'Respuesta de error de validación',
    type: 'object',
    required: ['message', 'errors'],
    properties: [
        new OA\Property(property: 'message', description: 'Mensaje de error general', type: 'string', example: 'The given data was invalid.'),
        new OA\Property(
            property: 'errors',
            description: 'Errores de validación por campo',
            type: 'object',
            additionalProperties: new OA\AdditionalProperties(
                type: 'array',
                items: new OA\Items(type: 'string')
            ),
            example: [
                'name' => ['The name field is required.'],
                'monthly_price' => ['The monthly price must be an integer.'],
                'features' => ['The features field is required.']
            ]
        )
    ]
)]
#[OA\Schema(
    schema: 'UnauthorizedResponse',
    title: 'Unauthorized Response',
    description: 'Respuesta de error de autenticación',
    type: 'object',
    required: ['message'],
    properties: [
        new OA\Property(property: 'message', description: 'Mensaje de error de autenticación', type: 'string', example: 'Unauthenticated.')
    ]
)]
#[OA\Schema(
    schema: 'ForbiddenResponse',
    title: 'Forbidden Response',
    description: 'Respuesta de error de autorización',
    type: 'object',
    required: ['message'],
    properties: [
        new OA\Property(property: 'message', description: 'Mensaje de error de autorización', type: 'string', example: 'This action is unauthorized.')
    ]
)]
class SwaggerController extends Controller
{
    // Esta clase solo contiene las anotaciones de Swagger
}