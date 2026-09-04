<?php

declare(strict_types=1);

namespace App\Domains\ProductTest\Http\Controllers;

use App\Domains\ProductTest\Actions\CreateProductTestAction;
use App\Domains\ProductTest\Actions\UpdateProductTestAction;
use App\Domains\ProductTest\Actions\DeleteProductTestAction;
use App\Domains\ProductTest\Actions\BulkDeleteProductTestAction;
use App\Domains\ProductTest\Actions\ExportProductTestAction;
use App\Domains\ProductTest\DTOs\CreateProductTestDTO;
use App\Domains\ProductTest\DTOs\UpdateProductTestDTO;
use App\Domains\ProductTest\Models\ProductTest;
use App\Domains\ProductTest\Requests\CreateProductTestRequest;
use App\Domains\ProductTest\Requests\UpdateProductTestRequest;
use App\Domains\ProductTest\Resources\ProductTestResource;
use App\Domains\ProductTest\Repositories\Contracts\ProductTestRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductTestController extends Controller
{
    public function __construct(
        protected ProductTestRepositoryInterface $repository
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->wantsJson()) {
            $records = ProductTest::latest()->paginate($request->input('per_page', 15));
            return response()->json([
                'status' => 'success',
                'data' => ProductTestResource::collection($records),
                'meta' => [
                    'current_page' => $records->currentPage(),
                    'last_page' => $records->lastPage(),
                    'total' => $records->total(),
                ]
            ]);
        }

        $records = ProductTest::latest()->paginate(15);
        return view('admin.product_test.index', compact('records'));
    }

    public function create(): View
    {
        return view('admin.product_test.create');
    }

    public function store(CreateProductTestRequest $request, CreateProductTestAction $action): RedirectResponse|JsonResponse
    {
        $dto = CreateProductTestDTO::fromRequest($request);
        $record = $action->execute($dto);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'ProductTest created successfully.',
                'data' => new ProductTestResource($record)
            ], 201);
        }

        return redirect()->route('admin.product_test.index')->with('success', 'ProductTest created successfully.');
    }

    public function show(string $id, Request $request): View|JsonResponse
    {
        $record = $this->repository->findOrFail($id);
        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => new ProductTestResource($record)
            ]);
        }

        return view('admin.product_test.show', compact('record'));
    }

    public function edit(string $id): View
    {
        $record = $this->repository->findOrFail($id);
        return view('admin.product_test.edit', compact('record'));
    }

    public function update(UpdateProductTestRequest $request, string $id, UpdateProductTestAction $action): RedirectResponse|JsonResponse
    {
        $dto = UpdateProductTestDTO::fromRequest($request);
        $record = $action->execute($id, $dto);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'ProductTest updated successfully.',
                'data' => new ProductTestResource($record)
            ]);
        }

        return redirect()->route('admin.product_test.index')->with('success', 'ProductTest updated successfully.');
    }

    public function destroy(string $id, Request $request, DeleteProductTestAction $action): RedirectResponse|JsonResponse
    {
        $action->execute($id);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'ProductTest deleted successfully.'
            ]);
        }

        return redirect()->route('admin.product_test.index')->with('success', 'ProductTest deleted successfully.');
    }

    public function bulkDestroy(Request $request, BulkDeleteProductTestAction $action): JsonResponse
    {
        $ids = (array)$request->input('ids', []);
        $count = $action->execute($ids);
        return response()->json([
            'status' => 'success',
            'message' => "{$count} records deleted successfully."
        ]);
    }

    public function export(Request $request, ExportProductTestAction $action): JsonResponse
    {
        $data = $action->execute($request->input('format', 'csv'));
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}