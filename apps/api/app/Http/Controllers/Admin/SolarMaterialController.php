<?php

namespace App\Http\Controllers\Admin;

use App\Actions\LogActivityAction;
use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\SolarMaterial;
use App\Models\SolarMaterialCategory;
use App\Models\SolarMaterialCompany;
use App\Models\SolarProjectBom;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Controller for managing Solar Materials Catalog, Brands, and Project Bill of Materials (BOM).
 */
class SolarMaterialController extends Controller
{
    protected LogActivityAction $activityLogger;

    public function __construct(LogActivityAction $activityLogger)
    {
        $this->activityLogger = $activityLogger;
    }

    /**
     * Display materials catalog list.
     */
    public function index(Request $request): View
    {
        $query = SolarMaterial::with(['category', 'company'])->latest();

        if ($request->filled('search')) {
            $s = $request->search;
            $query->where(function ($q) use ($s) {
                $q->where('name', 'like', "%{$s}%")
                    ->orWhere('model_number', 'like', "%{$s}%")
                    ->orWhere('specifications', 'like', "%{$s}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('company_id')) {
            $query->where('company_id', $request->company_id);
        }

        $materials = $query->paginate(25);
        $categories = SolarMaterialCategory::orderBy('sort_order')->get();
        $companies = SolarMaterialCompany::orderBy('name')->get();

        return view('admin.materials.index', compact('materials', 'categories', 'companies'));
    }

    /**
     * Display categories list with item counts.
     */
    public function categories(): View
    {
        $categories = SolarMaterialCategory::withCount('materials')->orderBy('sort_order')->get();
        return view('admin.materials.categories', compact('categories'));
    }

    /**
     * Store new material catalog item.
     */
    public function store(Request $request): RedirectResponse|JsonResponse
    {
        $validated = $request->validate([
            'category_id' => 'required|exists:crm_solar_material_categories,id',
            'company_id' => 'nullable|exists:crm_solar_material_companies,id',
            'name' => 'required|string|max:255',
            'model_number' => 'nullable|string|max:150',
            'specifications' => 'nullable|string|max:255',
            'unit' => 'required|string|max:50',
            'purchase_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'gst_percent' => 'required|numeric|min:0|max:100',
            'warranty_years' => 'required|integer|min:0',
            'in_stock' => 'required|integer|min:0',
        ]);

        $material = SolarMaterial::create($validated);
        $this->activityLogger->execute($material, 'status_change', "Catalog item '{$material->name}' added.");

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'Material added successfully', 'material' => $material]);
        }

        return redirect()->back()->with('success', 'Solar material added to catalog!');
    }

    /**
     * Allocate material item to a project's Bill of Materials (BOM).
     */
    public function storeBomItem(Request $request, Project $project): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'material_id' => 'required|exists:crm_solar_materials,id',
            'quantity' => 'required|numeric|min:0.01',
            'unit_rate' => 'required|numeric|min:0',
            'gst_percent' => 'required|numeric|min:0',
            'status' => 'required|string',
            'serial_numbers' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        $totalCost = ($validated['quantity'] * $validated['unit_rate']) * (1 + ($validated['gst_percent'] / 100));
        $validated['project_id'] = $project->id;
        $validated['total_cost'] = $totalCost;

        $bom = SolarProjectBom::create($validated);
        $this->activityLogger->execute($project, 'status_change', "BOM kit updated with {$bom->quantity} units.");

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'BOM item allocated successfully!', 'bom' => $bom->load('material.category', 'material.company')]);
        }

        return redirect()->back()->with('success', 'Item allocated to project Bill of Materials!');
    }

    /**
     * Update quantity or status of a project BOM item.
     */
    public function updateBomItem(Request $request, SolarProjectBom $bom): JsonResponse|RedirectResponse
    {
        $validated = $request->validate([
            'quantity' => 'nullable|numeric|min:0.01',
            'unit_rate' => 'nullable|numeric|min:0',
            'status' => 'nullable|string',
            'serial_numbers' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if (isset($validated['quantity']) || isset($validated['unit_rate'])) {
            $qty = $validated['quantity'] ?? $bom->quantity;
            $rate = $validated['unit_rate'] ?? $bom->unit_rate;
            $validated['total_cost'] = ($qty * $rate) * (1 + ($bom->gst_percent / 100));
        }

        $bom->update($validated);

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'BOM item updated successfully!', 'bom' => $bom->fresh('material.category')]);
        }

        return redirect()->back()->with('success', 'BOM item updated!');
    }

    /**
     * Delete BOM item.
     */
    public function destroyBomItem(Request $request, SolarProjectBom $bom): JsonResponse|RedirectResponse
    {
        $bom->delete();

        if ($request->ajax()) {
            return response()->json(['success' => true, 'message' => 'BOM item removed']);
        }

        return redirect()->back()->with('success', 'BOM item removed successfully');
    }
}
