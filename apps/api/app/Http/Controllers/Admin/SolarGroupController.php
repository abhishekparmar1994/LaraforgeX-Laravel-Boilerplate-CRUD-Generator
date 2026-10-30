<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SolarCustomerGroup;
use App\Models\SolarProductGroup;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Class SolarGroupController
 * Manages Product Groups and related Customer Groups for Solar EPC leads.
 */
class SolarGroupController extends Controller
{
    /**
     * Purpose: List all active product groups with their related customer groups.
     * Parameters: Request $request
     * Return: JsonResponse
     */
    public function indexProductGroups(Request $request): JsonResponse
    {
        $groups = SolarProductGroup::with(['customerGroups' => function ($q) {
            $q->where('status', 'active')->orderBy('name');
        }])->where('status', 'active')->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $groups,
        ]);
    }

    /**
     * Purpose: Store a new solar product group into crm_solar_product_groups table.
     * Parameters: Request $request
     * Return: JsonResponse
     * Side Effects: Inserts a new record into crm_solar_product_groups
     */
    public function storeProductGroup(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150|unique:crm_solar_product_groups,name',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Product group name is required.',
            'name.unique' => 'This product group name already exists.',
        ]);

        $group = SolarProductGroup::create(array_merge($validated, ['status' => 'active']));

        return response()->json([
            'success' => true,
            'message' => 'Product group registered successfully',
            'data' => $group,
        ], 201);
    }

    /**
     * Purpose: List customer groups optionally filtered by product_group_id.
     * Parameters: Request $request
     * Return: JsonResponse
     */
    public function indexCustomerGroups(Request $request): JsonResponse
    {
        $query = SolarCustomerGroup::with('productGroup')->where('status', 'active');

        if ($request->filled('product_group_id')) {
            $query->where(function ($q) use ($request) {
                $q->where('product_group_id', $request->product_group_id)
                  ->orWhereNull('product_group_id');
            });
        }

        $customers = $query->orderBy('name')->get();

        return response()->json([
            'success' => true,
            'data' => $customers,
        ]);
    }

    /**
     * Purpose: Store a new customer group linked to a product group.
     * Parameters: Request $request
     * Return: JsonResponse
     * Side Effects: Inserts a new record into crm_solar_customer_groups
     */
    public function storeCustomerGroup(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:150',
            'product_group_id' => 'nullable|exists:crm_solar_product_groups,id',
            'code' => 'nullable|string|max:50',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Customer group name is required.',
            'product_group_id.exists' => 'Selected product group does not exist.',
        ]);

        $group = SolarCustomerGroup::create(array_merge($validated, ['status' => 'active']));

        return response()->json([
            'success' => true,
            'message' => 'Customer group registered successfully',
            'data' => $group,
        ], 201);
    }
}
