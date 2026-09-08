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
        return response()->json([
            'products' => TefaProduct::with('program')
                ->where('stock', '>', 0)
                ->when($request->search, fn ($q) => $q->where('product_name', 'like', "%{$request->search}%"))
                ->latest()
                ->get(),
        ]);
    }

    /**
     * POST /api/public/orders — public order submission
     */
    public function placeOrder(Request $request): JsonResponse
    {
        $data = $request->validate([
            'buyer_name' => ['nullable', 'string', 'max:255'],
            'customer_name' => ['nullable', 'string', 'max:255'],
            'buyer_contact' => ['nullable', 'string', 'max:100'],
            'customer_phone' => ['nullable', 'string', 'max:100'],
            'delivery_address' => ['nullable', 'string'],
            'notes' => ['nullable', 'string'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:tefa_products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        if (empty($data['buyer_name']) && ! empty($data['customer_name'])) {
            $data['buyer_name'] = $data['customer_name'];
        }
        if (empty($data['buyer_contact']) && ! empty($data['customer_phone'])) {
            $data['buyer_contact'] = $data['customer_phone'];
        }

        if (empty($data['buyer_name'])) {
            $data['buyer_name'] = 'Pelanggan';
        }

        $order = $this->orderService->placeOrder($data);

        return response()->json([
            'message' => 'Order placed successfully.',
            'order_code' => $order->order_code,
            'total' => $order->total_price,
            'order' => $order,
        ], 201);
    }
}
