<?php

namespace App\Presentation\Controllers\Api\V1;

use App\Application\DTOs\Empresa\CreateEmpresaDTO;
use App\Application\DTOs\Empresa\UpdateEmpresaDTO;
use App\Application\UseCases\Empresa\CreateEmpresaUseCase;
use App\Application\UseCases\Empresa\DeleteEmpresaUseCase;
use App\Application\UseCases\Empresa\GetEmpresaUseCase;
use App\Application\UseCases\Empresa\ListEmpresasUseCase;
use App\Application\UseCases\Empresa\UpdateEmpresaUseCase;
use App\Http\Controllers\Controller;
use App\Presentation\Requests\Empresa\CreateEmpresaRequest;
use App\Presentation\Requests\Empresa\UpdateEmpresaRequest;
use App\Presentation\Resources\Empresa\EmpresaResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controlador API para gestión de empresas.
 * Maneja las operaciones CRUD de empresas con validación de límites según planes.
 * 
 * @OA\Tag(name="Empresas", description="Gestión de empresas tenant")
 */
class EmpresaController extends Controller
{
    public function __construct(
        private CreateEmpresaUseCase $createEmpresaUseCase,
        private GetEmpresaUseCase $getEmpresaUseCase,
        private ListEmpresasUseCase $listEmpresasUseCase,
        private UpdateEmpresaUseCase $updateEmpresaUseCase,
        private DeleteEmpresaUseCase $deleteEmpresaUseCase
    ) {
    }

    /**
     * Lista todas las empresas con paginación.
     *
     * @OA\Get(
     *     path="/api/v1/empresas",
     *     summary="Listar empresas",
     *     description="Obtiene una lista paginada de todas las empresas registradas",
     *     tags={"Empresas"},
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
     *         description="Lista de empresas obtenida exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", type="array", @OA\Items(ref="#/components/schemas/Empresa")),
     *             @OA\Property(property="pagination", ref="#/components/schemas/Pagination")
     *         )
     *     ),
     *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
     *     @OA\Response(response=403, ref="#/components/responses/Forbidden")
     * )
     */
    public function index(Request $request): JsonResponse
    {
        try {
            $page = (int) $request->get('page', 1);
            $limit = (int) $request->get('limit', 10);

            $result = $this->listEmpresasUseCase->execute($page, $limit);

            return response()->json([
                'status' => 'success',
                'data' => EmpresaResource::collection($result['data']),
                'pagination' => $result['pagination']
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Crea una nueva empresa.
     *
     * @OA\Post(
     *     path="/api/v1/empresas",
     *     summary="Crear empresa",
     *     description="Crea una nueva empresa con un plan asignado",
     *     tags={"Empresas"},
     *     security={{"bearerAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "email", "plan_id"},
     *             @OA\Property(property="nombre", type="string", example="Empresa Demo S.L."),
     *             @OA\Property(property="email", type="string", format="email", example="contacto@empresademo.com"),
     *             @OA\Property(property="plan_id", type="string", format="uuid", example="123e4567-e89b-12d3-a456-426614174000")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Empresa creada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Empresa creada exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Empresa")
     *         )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/BadRequest"),
     *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
     *     @OA\Response(response=403, ref="#/components/responses/Forbidden"),
     *     @OA\Response(response=422, ref="#/components/responses/ValidationError")
     * )
     */
    public function store(CreateEmpresaRequest $request): JsonResponse
    {
        try {
            $dto = CreateEmpresaDTO::fromArray($request->validated());
            $empresa = $this->createEmpresaUseCase->execute($dto);

            return response()->json([
                'status' => 'success',
                'message' => 'Empresa creada exitosamente',
                'data' => new EmpresaResource($empresa)
            ], 201);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Muestra una empresa específica.
     *
     * @OA\Get(
     *     path="/api/v1/empresas/{id}",
     *     summary="Obtener empresa",
     *     description="Obtiene los detalles de una empresa específica",
     *     tags={"Empresas"},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la empresa",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Detalles de la empresa",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="data", ref="#/components/schemas/Empresa")
     *         )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/BadRequest"),
     *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
     *     @OA\Response(response=403, ref="#/components/responses/Forbidden"),
     *     @OA\Response(response=404, ref="#/components/responses/NotFound")
     * )
     */
    public function show(string $id): JsonResponse
    {
        try {
            $empresa = $this->getEmpresaUseCase->execute($id);

            return response()->json([
                'status' => 'success',
                'data' => new EmpresaResource($empresa)
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 404);
        }
    }

    /**
     * Actualiza una empresa existente.
     *
     * @OA\Put(
     *     path="/api/v1/empresas/{id}",
     *     summary="Actualizar empresa",
     *     description="Actualiza los datos de una empresa existente, incluyendo cambio de plan opcional",
     *     tags={"Empresas"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la empresa",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"nombre", "email"},
     *             @OA\Property(property="nombre", type="string", example="Empresa Demo S.L."),
     *             @OA\Property(property="email", type="string", format="email", example="contacto@empresademo.com"),
     *             @OA\Property(property="plan_id", type="string", format="uuid", example="123e4567-e89b-12d3-a456-426614174000"),
     *             @OA\Property(property="motivo_cambio", type="string", example="Upgrade por crecimiento del equipo")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Empresa actualizada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Empresa actualizada exitosamente"),
     *             @OA\Property(property="data", ref="#/components/schemas/Empresa")
     *         )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/BadRequest"),
     *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
     *     @OA\Response(response=403, ref="#/components/responses/Forbidden"),
     *     @OA\Response(response=404, ref="#/components/responses/NotFound"),
     *     @OA\Response(response=422, ref="#/components/responses/ValidationError")
     * )
     */
    public function update(UpdateEmpresaRequest $request, string $id): JsonResponse
    {
        try {
            $dto = UpdateEmpresaDTO::fromArray($request->validated());
            $empresa = $this->updateEmpresaUseCase->execute($id, $dto);

            return response()->json([
                'status' => 'success',
                'message' => 'Empresa actualizada exitosamente',
                'data' => new EmpresaResource($empresa)
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        } catch (\DomainException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }

    /**
     * Elimina una empresa.
     *
     * @OA\Delete(
     *     path="/api/v1/empresas/{id}",
     *     summary="Eliminar empresa",
     *     description="Elimina una empresa del sistema (solo si no tiene usuarios activos)",
     *     tags={"Empresas"},
     *     security={{"bearerAuth": {}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="ID de la empresa",
     *         required=true,
     *         @OA\Schema(type="string", format="uuid")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Empresa eliminada exitosamente",
     *         @OA\JsonContent(
     *             @OA\Property(property="status", type="string", example="success"),
     *             @OA\Property(property="message", type="string", example="Empresa eliminada exitosamente")
     *         )
     *     ),
     *     @OA\Response(response=400, ref="#/components/responses/BadRequest"),
     *     @OA\Response(response=401, ref="#/components/responses/Unauthorized"),
     *     @OA\Response(response=403, ref="#/components/responses/Forbidden"),
     *     @OA\Response(response=404, ref="#/components/responses/NotFound")
     * )
     */
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->deleteEmpresaUseCase->execute($id);

            return response()->json([
                'status' => 'success',
                'message' => 'Empresa eliminada exitosamente'
            ]);
        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 404);
        } catch (\DomainException $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 400);
        }
    }
}