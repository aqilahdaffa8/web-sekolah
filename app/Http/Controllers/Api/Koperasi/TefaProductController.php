<?php

namespace App\Http\Controllers\Api\Koperasi;

use App\Http\Controllers\Controller;
use App\Models\TefaProduct;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TefaProductController extends Controller
{
    public function __construct(private ActivityLogService $logger) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            TefaProduct::with('program')
                ->when($request->search, fn ($q) => $q->where('product_name', 'like', "%{$request->search}%"))
                ->paginate(15)
        );
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'product_name' => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'price'        => ['required', 'numeric', 'min:0'],
            'stock'        => ['required', 'integer', 'min:0'],
            'image_url'    => ['nullable', 'string'],
            'program_id'   => ['required', 'integer', 'exists:programs,id'],
        ]);

        $product = TefaProduct::create($data);
        $this->logger->log($request->user()->id, 'created', 'tefa_products');

        return response()->json(['message' => 'Product created.', 'product' => $product->load('program')], 201);
    }

    public function show(TefaProduct $tefaProduct): JsonResponse
    {
        return response()->json($tefaProduct->load('program'));
    }

    public function update(Request $request, TefaProduct $tefaProduct): JsonResponse
    {
        $data = $request->validate([
            'product_name' => ['sometimes', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'price'        => ['sometimes', 'numeric', 'min:0'],
            'stock'        => ['sometimes', 'integer', 'min:0'],
            'image_url'    => ['nullable', 'string'],
            'program_id'   => ['sometimes', 'integer', 'exists:programs,id'],
        ]);

        $tefaProduct->update($data);
        $this->logger->log($request->user()->id, 'updated', 'tefa_products');

        return response()->json(['message' => 'Product updated.', 'product' => $tefaProduct]);
    }

    public function destroy(Request $request, TefaProduct $tefaProduct): JsonResponse
    {
        $this->logger->log($request->user()->id, 'deleted', 'tefa_products');
        $tefaProduct->delete();

        return response()->json(['message' => 'Product deleted.']);
    }
}
