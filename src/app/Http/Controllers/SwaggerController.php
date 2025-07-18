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
    name: 'Plans',
    description: 'Gestión de planes de suscripción'
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