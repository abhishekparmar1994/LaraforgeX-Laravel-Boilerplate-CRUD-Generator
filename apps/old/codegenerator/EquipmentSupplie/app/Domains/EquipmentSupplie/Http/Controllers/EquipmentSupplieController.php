<?php

declare(strict_types=1);

namespace App\Domains\EquipmentSupplie\Http\Controllers;

use App\Domains\EquipmentSupplie\Actions\CreateEquipmentSupplieAction;
use App\Domains\EquipmentSupplie\Actions\UpdateEquipmentSupplieAction;
use App\Domains\EquipmentSupplie\Actions\DeleteEquipmentSupplieAction;
use App\Domains\EquipmentSupplie\Actions\BulkDeleteEquipmentSupplieAction;
use App\Domains\EquipmentSupplie\Actions\ExportEquipmentSupplieAction;
use App\Domains\EquipmentSupplie\DTOs\CreateEquipmentSupplieDTO;
use App\Domains\EquipmentSupplie\DTOs\UpdateEquipmentSupplieDTO;
use App\Domains\EquipmentSupplie\Models\EquipmentSupplie;
use App\Domains\EquipmentSupplie\Requests\CreateEquipmentSupplieRequest;
use App\Domains\EquipmentSupplie\Requests\UpdateEquipmentSupplieRequest;
use App\Domains\EquipmentSupplie\Resources\EquipmentSupplieResource;
use App\Domains\EquipmentSupplie\Repositories\Contracts\EquipmentSupplieRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EquipmentSupplieController extends Controller
{
    public function __construct(
        protected EquipmentSupplieRepositoryInterface $repository
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->wantsJson()) {
            $records = EquipmentSupplie::latest()->paginate($request->input('per_page', 15));
            return response()->json([
                'status' => 'success',
                'data' => EquipmentSupplieResource::collection($records),
                'meta' => [
                    'current_page' => $records->currentPage(),
                    'last_page' => $records->lastPage(),
                    'total' => $records->total(),
                ]
            ]);
        }

        return view('admin.equipment_supplie.index');
    }

    public function store(CreateEquipmentSupplieRequest $request, CreateEquipmentSupplieAction $action): JsonResponse
    {
        $dto = CreateEquipmentSupplieDTO::fromRequest($request);
        $record = $action->execute($dto);

        return response()->json([
            'status' => 'success',
            'message' => 'EquipmentSupplie created successfully.',
            'data' => new EquipmentSupplieResource($record)
        ], 201);
    }

    public function show(string $id): JsonResponse
    {
        $record = $this->repository->findOrFail($id);
        return response()->json([
            'status' => 'success',
            'data' => new EquipmentSupplieResource($record)
        ]);
    }

    public function update(UpdateEquipmentSupplieRequest $request, string $id, UpdateEquipmentSupplieAction $action): JsonResponse
    {
        $dto = UpdateEquipmentSupplieDTO::fromRequest($request);
        $record = $action->execute($id, $dto);

        return response()->json([
            'status' => 'success',
            'message' => 'EquipmentSupplie updated successfully.',
            'data' => new EquipmentSupplieResource($record)
        ]);
    }

    public function destroy(string $id, DeleteEquipmentSupplieAction $action): JsonResponse
    {
        $action->execute($id);
        return response()->json([
            'status' => 'success',
            'message' => 'EquipmentSupplie deleted successfully.'
        ]);
    }

    public function bulkDestroy(Request $request, BulkDeleteEquipmentSupplieAction $action): JsonResponse
    {
        $ids = (array)$request->input('ids', []);
        $count = $action->execute($ids);
        return response()->json([
            'status' => 'success',
            'message' => "{$count} records deleted successfully."
        ]);
    }

    public function export(Request $request, ExportEquipmentSupplieAction $action): JsonResponse
    {
        $data = $action->execute($request->input('format', 'csv'));
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}