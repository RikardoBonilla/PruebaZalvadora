<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Infrastructure\Models\UserModel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

class AuthController extends Controller
{
    #[OA\Post(
        path: '/api/v1/auth/login',
        operationId: 'login',
        description: 'Autentica un usuario y devuelve un token de acceso para usar en endpoints protegidos',
        summary: 'Iniciar sesión y obtener token',
        tags: ['Authentication'],
    )]
    #[OA\RequestBody(
        description: 'Credenciales de acceso',
        required: true,
        content: new OA\JsonContent(
            required: ['email', 'password'],
            properties: [
                new OA\Property(property: 'email', description: 'Email del usuario', type: 'string', format: 'email', example: 'admin@example.com'),
                new OA\Property(property: 'password', description: 'Contraseña del usuario', type: 'string', format: 'password', example: 'password'),
                new OA\Property(property: 'device_name', description: 'Nombre del dispositivo (opcional)', type: 'string', example: 'Mi dispositivo')
            ]
        )
    )]
    #[OA\Response(
        response: 200,
        description: 'Login exitoso',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'access_token', description: 'Token de acceso Bearer', type: 'string', example: '1|abcdef123456...'),
                new OA\Property(property: 'token_type', description: 'Tipo de token', type: 'string', example: 'Bearer'),
                new OA\Property(
                    property: 'user',
                    description: 'Datos del usuario autenticado',
                    type: 'object',
                    properties: [
                        new OA\Property(property: 'id', type: 'string', format: 'uuid'),
                        new OA\Property(property: 'name', type: 'string', example: 'Admin User'),
                        new OA\Property(property: 'email', type: 'string', format: 'email', example: 'admin@example.com'),
                        new OA\Property(property: 'role', type: 'string', enum: ['admin', 'user'], example: 'admin')
                    ]
                )
            ]
        )
    )]
    #[OA\Response(
        response: 422,
        description: 'Credenciales incorrectas',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'The provided credentials are incorrect.')
            ]
        )
    )]
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'device_name' => 'sometimes|string'
        ]);

        $user = UserModel::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $deviceName = $request->device_name ?? 'API Token';
        $token = $user->createToken($deviceName)->plainTextToken;

        return response()->json([
            'access_token' => $token,
            'token_type' => 'Bearer',
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role
            ]
        ]);
    }

    #[OA\Post(
        path: '/api/v1/auth/logout',
        operationId: 'logout',
        description: 'Revoca el token actual del usuario autenticado',
        summary: 'Cerrar sesión',
        security: [['bearerAuth' => []]],
        tags: ['Authentication'],
    )]
    #[OA\Response(
        response: 200,
        description: 'Logout exitoso',
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Logged out successfully')
            ]
        )
    )]
    #[OA\Response(
        response: 401,
        description: 'No autenticado',
        content: new OA\JsonContent(ref: '#/components/schemas/UnauthorizedResponse')
    )]
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }
}