<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProduct;
use App\Http\Requests\UpdateProduct;
use App\Models\Product;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $products = Product::all();

        return response()->json($products);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  StoreProduct  $request
     * @return JsonResponse
     */
    public function store(StoreProduct $request): JsonResponse
    {
        $data = $request->validated();

        $product = Product::create($data);

        return response()->json($product, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $uuid
     * @return JsonResponse
     */
    public function show(string $uuid): JsonResponse
    {
        $product = Product::where('uuid', $uuid)
            ->firstOrFail();

        return response()->json($product);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  UpdateProduct  $request
     * @param  string  $uuid
     * @return JsonResponse
     */
    public function update(UpdateProduct $request, string $uuid): JsonResponse
    {
        $data = $request->validated();

        $product = Product::where('uuid', $uuid)
            ->firstOrFail();

        if ($product->update($data)) {
            return response()->json($product);
        }

        return response()->json([
            'message' => 'Error updating product',
        ], 500);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  string  $uuid
     * @return JsonResponse
     */
    public function destroy(string $uuid): JsonResponse
    {
        $product = Product::where('uuid', $uuid)
            ->firstOrFail();

        if ($product->delete()) {
            return response()->json([
                'message' => 'Product deleted successfully',
            ]);
        }

        return response()->json([
            'message' => 'Error deleting product',
        ]);
    }
}
