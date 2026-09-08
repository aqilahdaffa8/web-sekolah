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
            'description' => ['nullable', 'string'],
            'price' => ['required', 'numeric', 'min:0'],
            'stock' => ['required', 'integer', 'min:0'],
            'image_url' => ['nullable', 'string'],
            'program_id' => ['required', 'integer', 'exists:programs,id'],
        ]);

        $product = TefaProduct::create($data);
        $this->logger->log($request->user()->id, 'created', 'tefa_products');

        return response()->json(['message' => 'Product created.', 'product' => $product->load('program')], 201);
    }

    public function show($product): JsonResponse
    {
        $tefaProduct = $product instanceof TefaProduct ? $product : TefaProduct::findOrFail($product);
        return response()->json($tefaProduct->load('program'));
    }

    public function update(Request $request, $product): JsonResponse
    {
        $tefaProduct = $product instanceof TefaProduct ? $product : TefaProduct::findOrFail($product);

        $data = $request->validate([
            'product_name' => ['sometimes', 'string', 'max:255'],
            'name' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'price' => ['sometimes', 'numeric', 'min:0'],
            'stock' => ['sometimes', 'integer', 'min:0'],
            'image_url' => ['nullable', 'string'],
            'program_id' => ['sometimes', 'integer', 'exists:programs,id'],
        ]);

        if (empty($data['product_name']) && ! empty($data['name'])) {
            $data['product_name'] = $data['name'];
        }

        $tefaProduct->update($data);
        $this->logger->log($request->user()->id, 'updated', 'tefa_products', $tefaProduct->id);

        return response()->json(['message' => 'Produk berhasil diperbarui.', 'product' => $tefaProduct]);
    }

    public function destroy(Request $request, $product): JsonResponse
    {
        $tefaProduct = $product instanceof TefaProduct ? $product : TefaProduct::findOrFail($product);

        // Delete associated order items first to avoid FK restrict constraint failures
        \Illuminate\Support\Facades\DB::table('tefa_order_items')->where('product_id', $tefaProduct->id)->delete();

        $this->logger->log($request->user()->id, 'deleted', 'tefa_products', $tefaProduct->id);
        $tefaProduct->delete();

        return response()->json(['message' => 'Produk berhasil dihapus.']);
    }
}
