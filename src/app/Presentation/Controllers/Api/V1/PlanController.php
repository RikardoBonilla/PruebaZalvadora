<?php

declare(strict_types=1);

namespace App\Presentation\Controllers\Api\V1;

use App\Application\DTOs\Plan\CreatePlanDTO;
use App\Application\DTOs\Plan\UpdatePlanDTO;
use App\Application\UseCases\Plan\CreatePlanUseCase;
use App\Application\UseCases\Plan\DeletePlanUseCase;
use App\Application\UseCases\Plan\GetPlanUseCase;
use App\Application\UseCases\Plan\ListPlansUseCase;
use App\Application\UseCases\Plan\UpdatePlanUseCase;
use App\Http\Controllers\Controller;
use App\Presentation\Requests\Plan\CreatePlanRequest;
use App\Presentation\Requests\Plan\UpdatePlanRequest;
use App\Presentation\Resources\Plan\PlanResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use InvalidArgumentException;
use OpenApi\Attributes as OA;

class PlanController extends Controller
{
    public function __construct(
        private readonly CreatePlanUseCase $createPlanUseCase,
        private readonly UpdatePlanUseCase $updatePlanUseCase,
        private readonly DeletePlanUseCase $deletePlanUseCase,
        private readonly GetPlanUseCase $getPlanUseCase,
        private readonly ListPlansUseCase $listPlansUseCase,
    ) {
    }

    #[OA\Get(
        path: '/api/v1/plans',
        operationId: 'getPlans',
        description: 'Obtiene la lista de todos los planes disponibles. Este endpoint es público y no requiere autenticación.',
        summary: 'Listar planes',
        tags: ['Plans'],
    )]
    #[OA\Response(
        response: 200,
        description: 'Lista de planes obtenida exitosamente',
        content: new OA\JsonContent(ref: '#/components/schemas/PlanCollection')
    )]
    public function index(): AnonymousResourceCollection
    {
        $plans = $this->listPlansUseCase->execute();

        return PlanResource::collection($plans);
    }

    #[OA\Post(
        path: '/api/v1/plans',
        operationId: 'createPlan',
        description: 'Crea un nuevo plan de suscripción con las características especificadas',
        summary: 'Crear un plan',
        security: [['bearerAuth' => []]],
        tags: ['Plans'],
    )]
    #[OA\RequestBody(
        description: 'Datos del plan a crear',
        required: true,
        content: new OA\JsonContent(ref: '#/components/schemas/CreatePlanRequest')
    )]
    #[OA\Response(
        response: 201,
        description: 'Plan creado exitosamente',
        content: new OA\JsonContent(ref: '#/components/schemas/Plan')
    )]
    #[OA\Response(
        response: 401,
        description: 'No autenticado',
        content: new OA\JsonContent(ref: '#/components/schemas/UnauthorizedResponse')
    )]
    #[OA\Response(
        response: 403,
        description: 'No autorizado - Solo administradores',
        content: new OA\JsonContent(ref: '#/components/schemas/ForbiddenResponse')
    )]
    #[OA\Response(
        response: 422,
        description: 'Error de validación',
        content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')
    )]
    public function store(CreatePlanRequest $request): JsonResponse
    {
        try {
            $dto = new CreatePlanDTO(
                name: $request->validated('name'),
                monthlyPrice: $request->validated('monthly_price'),
                currency: $request->validated('currency'),
                userLimit: $request->validated('user_limit'),
                features: $request->validated('features'),
            );

            $plan = $this->createPlanUseCase->execute($dto);

            return response()->json(
                new PlanResource($plan),
                201
            );
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'error' => $e->getMessage(),
            ], 422);
        }
    }

    #[OA\Get(
        path: '/api/v1/plans/{id}',
        operationId: 'getPlan',
        description: 'Obtiene los detalles de un plan específico por su ID. Este endpoint es público y no requiere autenticación.',
        summary: 'Obtener un plan',
        tags: ['Plans'],
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'ID único del plan',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'string', format: 'uuid')
    )]
    #[OA\Response(
        response: 200,
        description: 'Plan encontrado exitosamente',
        content: new OA\JsonContent(ref: '#/components/schemas/Plan')
    )]
    #[OA\Response(
        response: 404,
        description: 'Plan no encontrado',
        content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
    )]
    public function show(string $id): JsonResponse
    {
        try {
            $plan = $this->getPlanUseCase->execute($id);

            return response()->json(new PlanResource($plan));
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'message' => 'Plan not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    #[OA\Put(
        path: '/api/v1/plans/{id}',
        operationId: 'updatePlan',
        description: 'Actualiza un plan existente con nuevos datos',
        summary: 'Actualizar un plan',
        security: [['bearerAuth' => []]],
        tags: ['Plans'],
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'ID único del plan a actualizar',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'string', format: 'uuid')
    )]
    #[OA\RequestBody(
        description: 'Nuevos datos del plan',
        required: true,
        content: new OA\JsonContent(ref: '#/components/schemas/UpdatePlanRequest')
    )]
    #[OA\Response(
        response: 200,
        description: 'Plan actualizado exitosamente',
        content: new OA\JsonContent(ref: '#/components/schemas/Plan')
    )]
    #[OA\Response(
        response: 401,
        description: 'No autenticado',
        content: new OA\JsonContent(ref: '#/components/schemas/UnauthorizedResponse')
    )]
    #[OA\Response(
        response: 403,
        description: 'No autorizado - Solo administradores',
        content: new OA\JsonContent(ref: '#/components/schemas/ForbiddenResponse')
    )]
    #[OA\Response(
        response: 404,
        description: 'Plan no encontrado',
        content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
    )]
    #[OA\Response(
        response: 422,
        description: 'Error de validación',
        content: new OA\JsonContent(ref: '#/components/schemas/ValidationErrorResponse')
    )]
    public function update(UpdatePlanRequest $request, string $id): JsonResponse
    {
        try {
            $dto = new UpdatePlanDTO(
                id: $id,
                name: $request->validated('name'),
                monthlyPrice: $request->validated('monthly_price'),
                currency: $request->validated('currency'),
                userLimit: $request->validated('user_limit'),
                features: $request->validated('features'),
            );

            $plan = $this->updatePlanUseCase->execute($dto);

            return response()->json(new PlanResource($plan));
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'message' => 'Plan not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }

    #[OA\Delete(
        path: '/api/v1/plans/{id}',
        operationId: 'deletePlan',
        description: 'Elimina un plan existente del sistema',
        summary: 'Eliminar un plan',
        security: [['bearerAuth' => []]],
        tags: ['Plans'],
    )]
    #[OA\Parameter(
        name: 'id',
        description: 'ID único del plan a eliminar',
        in: 'path',
        required: true,
        schema: new OA\Schema(type: 'string', format: 'uuid')
    )]
    #[OA\Response(
        response: 204,
        description: 'Plan eliminado exitosamente'
    )]
    #[OA\Response(
        response: 401,
        description: 'No autenticado',
        content: new OA\JsonContent(ref: '#/components/schemas/UnauthorizedResponse')
    )]
    #[OA\Response(
        response: 403,
        description: 'No autorizado - Solo administradores',
        content: new OA\JsonContent(ref: '#/components/schemas/ForbiddenResponse')
    )]
    #[OA\Response(
        response: 404,
        description: 'Plan no encontrado',
        content: new OA\JsonContent(ref: '#/components/schemas/ErrorResponse')
    )]
    public function destroy(string $id): JsonResponse
    {
        try {
            $this->deletePlanUseCase->execute($id);

            return response()->json(['message' => 'Plan deleted successfully'], 204);
        } catch (InvalidArgumentException $e) {
            return response()->json([
                'message' => 'Plan not found',
                'error' => $e->getMessage(),
            ], 404);
        }
    }
}