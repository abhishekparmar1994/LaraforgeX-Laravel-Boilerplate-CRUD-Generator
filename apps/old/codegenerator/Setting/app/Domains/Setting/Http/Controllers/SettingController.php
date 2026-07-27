<?php

declare(strict_types=1);

namespace App\Domains\Setting\Http\Controllers;

use App\Domains\Setting\Actions\CreateSettingAction;
use App\Domains\Setting\Actions\UpdateSettingAction;
use App\Domains\Setting\Actions\DeleteSettingAction;
use App\Domains\Setting\Actions\BulkDeleteSettingAction;
use App\Domains\Setting\Actions\ExportSettingAction;
use App\Domains\Setting\DTOs\CreateSettingDTO;
use App\Domains\Setting\DTOs\UpdateSettingDTO;
use App\Domains\Setting\Models\Setting;
use App\Domains\Setting\Requests\CreateSettingRequest;
use App\Domains\Setting\Requests\UpdateSettingRequest;
use App\Domains\Setting\Resources\SettingResource;
use App\Domains\Setting\Repositories\Contracts\SettingRepositoryInterface;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function __construct(
        protected SettingRepositoryInterface $repository
    ) {}

    public function index(Request $request): View|JsonResponse
    {
        if ($request->wantsJson()) {
            $records = Setting::latest()->paginate($request->input('per_page', 15));
            return response()->json([
                'status' => 'success',
                'data' => SettingResource::collection($records),
                'meta' => [
                    'current_page' => $records->currentPage(),
                    'last_page' => $records->lastPage(),
                    'total' => $records->total(),
                ]
            ]);
        }

        $records = Setting::latest()->paginate(15);
        return view('admin.setting.index', compact('records'));
    }

    public function create(): View
    {
        return view('admin.setting.create');
    }

    public function store(CreateSettingRequest $request, CreateSettingAction $action): RedirectResponse|JsonResponse
    {
        $dto = CreateSettingDTO::fromRequest($request);
        $record = $action->execute($dto);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Setting created successfully.',
                'data' => new SettingResource($record)
            ], 201);
        }

        return redirect()->route('admin.setting.index')->with('success', 'Setting created successfully.');
    }

    public function show(string $id, Request $request): View|JsonResponse
    {
        $record = $this->repository->findOrFail($id);
        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'data' => new SettingResource($record)
            ]);
        }

        return view('admin.setting.show', compact('record'));
    }

    public function edit(string $id): View
    {
        $record = $this->repository->findOrFail($id);
        return view('admin.setting.edit', compact('record'));
    }

    public function update(UpdateSettingRequest $request, string $id, UpdateSettingAction $action): RedirectResponse|JsonResponse
    {
        $dto = UpdateSettingDTO::fromRequest($request);
        $record = $action->execute($id, $dto);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Setting updated successfully.',
                'data' => new SettingResource($record)
            ]);
        }

        return redirect()->route('admin.setting.index')->with('success', 'Setting updated successfully.');
    }

    public function destroy(string $id, Request $request, DeleteSettingAction $action): RedirectResponse|JsonResponse
    {
        $action->execute($id);

        if ($request->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Setting deleted successfully.'
            ]);
        }

        return redirect()->route('admin.setting.index')->with('success', 'Setting deleted successfully.');
    }

    public function bulkDestroy(Request $request, BulkDeleteSettingAction $action): JsonResponse
    {
        $ids = (array)$request->input('ids', []);
        $count = $action->execute($ids);
        return response()->json([
            'status' => 'success',
            'message' => "{$count} records deleted successfully."
        ]);
    }

    public function export(Request $request, ExportSettingAction $action): JsonResponse
    {
        $data = $action->execute($request->input('format', 'csv'));
        return response()->json([
            'status' => 'success',
            'data' => $data
        ]);
    }
}