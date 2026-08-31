<?php

namespace App\Http\Controllers\Api\Public;

use App\Http\Controllers\Controller;
use App\Models\TefaProduct;
use App\Services\TefaOrderService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TefaPublicController extends Controller
{
    public function __construct(private TefaOrderService $orderService) {}

    /**
     * GET /api/public/tefa
     */
    public function index(Request $request): JsonResponse
    {
        return response()->json(
            TefaProduct::where('stock', '>', 0)
                ->when($request->search, fn ($q) => $q->where('name', 'like', "%{$request->search}%"))
                ->paginate(12)
        );
    }

    /**
     * POST /api/public/orders — public order submission
     */
    public function placeOrder(Request $request): JsonResponse
    {
        $data = $request->validate([
            'buyer_name'         => ['required', 'string', 'max:255'],
            'buyer_contact'      => ['nullable', 'string', 'max:100'],
            'items'              => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:tefa_products,id'],
            'items.*.quantity'   => ['required', 'integer', 'min:1'],
        ]);

        $order = $this->orderService->placeOrder($data);

        return response()->json([
            'message'    => 'Order placed successfully.',
            'order_code' => $order->order_code,
            'total'      => $order->total_price,
            'order'      => $order,
        ], 201);
    }
}
