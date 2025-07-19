<?php

namespace App\Presentation\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controlador API para gestión de usuarios de empresas.
 * Maneja las operaciones CRUD de usuarios dentro de empresas con validación de límites según planes.
 * 
 * @OA\Tag(name="Usuarios de Empresa", description="Gestión de usuarios dentro de empresas")
 */
class UsuarioEmpresaController extends Controller
{
    /**
     * Lista todos los usuarios de una empresa con paginación.
     *
     * @OA\Get(
     *     path="/api/v1/empresas/{empresa_id}/usuarios",
     *     summary="Listar usuarios de empresa",
     *     description="Obtiene una lista paginada de todos los usuarios de una empresa específica",
     *     tags={"Usuarios de Empresa"},
     *     @OA\Parameter(
     *         name="empresa_id",
     *         in="path",
     *         description="ID de la empresa",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Parameter(
     *         name="page",
     *         in="query",
     *         description="Número de página",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, default=1)
     *     ),
     *     @OA\Parameter(
     *         name="limit",
     *         in="query",
     *         description="Número de elementos por página",
     *         required=false,
     *         @OA\Schema(type="integer", minimum=1, maximum=100, default=10)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lista de usuarios obtenida exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/UsuarioEmpresa")),
     *             @OA\Property(property="pagination", ref="#/components/schemas/Pagination")
     *         )
     *     ),
     *     @OA\Response(response=404, ref="#/components/responses/NotFound")
     * )
     */
    public function index(Request $request, string $empresaId): JsonResponse
    {
        // Implementación pendiente
        return response()->json([
            'status' => 'success',
            'message' => 'Endpoint de usuarios de empresa implementado próximamente',
            'data' => [],
            'pagination' => [
                'current_page' => 1,
                'per_page' => 10,
                'total' => 0,
                'last_page' => 1
            ]
        ]);
    }

    /**
     * Crea un nuevo usuario en una empresa.
     *
     * @OA\Post(
     *     path="/api/v1/empresas/{empresa_id}/usuarios",
     *     summary="Crear usuario de empresa",
     *     description="Crea un nuevo usuario dentro de una empresa específica",
     *     tags={"Usuarios de Empresa"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="empresa_id",
     *         in="path",
     *         description="ID de la empresa",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/CreateUsuarioEmpresaRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Usuario creado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Usuario creado exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/UsuarioEmpresa")
     *         )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/BadRequest"),
     *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
     *     @OA\Response(response=403, ref="#/components/responses/Forbidden"),
     *     @OA\Response(response=404, ref="#/components/responses/NotFound"),
     *     @OA\Response(response=422, ref="#/components/responses/ValidationError")
     * )
     */
    public function store(Request $request, string $empresaId): JsonResponse
    {
        // Implementación pendiente
        return response()->json([
            'status' => 'success',
            'message' => 'Endpoint de creación de usuarios de empresa implementado próximamente'
        ], 201);
    }

    /**
     * Muestra un usuario específico de una empresa.
     *
     * @OA\Get(
     *     path="/api/v1/empresas/{empresa_id}/usuarios/{id}",
     *     summary="Obtener usuario de empresa",
     *     description="Obtiene los detalles de un usuario específico de una empresa",
     *     tags={"Usuarios de Empresa"},
     *     @OA\Parameter(
     *         name="empresa_id",
     *         in="path",
     *         description="ID de la empresa",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del usuario",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles del usuario",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", ref="#/components/schemas/UsuarioEmpresa")
     *         )
     *     ),
     *     @OA\Response(response=404, ref="#/components/responses/NotFound")
     * )
     */
    public function show(string $empresaId, string $id): JsonResponse
    {
        // Implementación pendiente
        return response()->json([
            'status' => 'success',
            'message' => 'Endpoint de obtener usuario de empresa implementado próximamente'
        ]);
    }

    /**
     * Actualiza un usuario existente de una empresa.
     *
     * @OA\Put(
     *     path="/api/v1/empresas/{empresa_id}/usuarios/{id}",
     *     summary="Actualizar usuario de empresa",
     *     description="Actualiza los datos de un usuario existente de una empresa",
     *     tags={"Usuarios de Empresa"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="empresa_id",
     *         in="path",
     *         description="ID de la empresa",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del usuario",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/UpdateUsuarioEmpresaRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario actualizado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Usuario actualizado exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/UsuarioEmpresa")
     *         )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/BadRequest"),
     *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
     *     @OA\Response(response=403, ref="#/components/responses/Forbidden"),
     *     @OA\Response(response=404, ref="#/components/responses/NotFound"),
     *     @OA\Response(response=422, ref="#/components/responses/ValidationError")
     * )
     */
    public function update(Request $request, string $empresaId, string $id): JsonResponse
    {
        // Implementación pendiente
        return response()->json([
            'status' => 'success',
            'message' => 'Endpoint de actualización de usuarios de empresa implementado próximamente'
        ]);
    }

    /**
     * Elimina un usuario de una empresa.
     *
     * @OA\Delete(
     *     path="/api/v1/empresas/{empresa_id}/usuarios/{id}",
     *     summary="Eliminar usuario de empresa",
     *     description="Elimina un usuario de una empresa del sistema",
     *     tags={"Usuarios de Empresa"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="empresa_id",
     *         in="path",
     *         description="ID de la empresa",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID del usuario",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Usuario eliminado exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Usuario eliminado exitosamente")
     *         )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/BadRequest"),
     *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
     *     @OA\Response(response=403, ref="#/components/responses/Forbidden"),
     *     @OA\Response(response=404, ref="#/components/responses/NotFound")
     * )
     */
    public function destroy(string $empresaId, string $id): JsonResponse
    {
        // Implementación pendiente
        return response()->json([
            'status' => 'success',
            'message' => 'Endpoint de eliminación de usuarios de empresa implementado próximamente'
        ]);
    }
}