<?php

namespace App\Http\Controllers\Api\Koperasi;

use App\Http\Controllers\Controller;
use App\Models\TefaOrder;
use App\Services\ActivityLogService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TefaOrderController extends Controller
{
    public function __construct(private ActivityLogService $logger) {}

    public function index(Request $request): JsonResponse
    {
        return response()->json(
            TefaOrder::with('items.product')
                ->when($request->status, fn ($q) => $q->where('status', $request->status))
                ->latest()
                ->paginate(15)
        );
    }

    public function show(TefaOrder $tefaOrder): JsonResponse
    {
        return response()->json($tefaOrder->load('items.product'));
    }

    public function updateStatus(Request $request, TefaOrder $tefaOrder): JsonResponse
    {
        $data = $request->validate([
            'status' => ['required', 'in:pending,paid,completed'],
        ]);

        $tefaOrder->update($data);
        $this->logger->log($request->user()->id, 'status_updated', 'tefa_orders', $tefaOrder->id, "New status: {$data['status']}");

        return response()->json(['message' => 'Order status updated.', 'order' => $tefaOrder]);
    }
}
