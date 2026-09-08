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
        $status = $request->query('status');
        $search = $request->query('search');

        $query = TefaOrder::with('items.product')
            ->when($status !== null && $status !== '', fn ($q) => $q->where('status', $status))
            ->when($search !== null && $search !== '', function ($q) use ($search) {
                $q->where(function ($sub) use ($search) {
                    $sub->where('buyer_name', 'like', "%{$search}%")
                        ->orWhere('order_code', 'like', "%{$search}%")
                        ->orWhereHas('items.product', function ($pq) use ($search) {
                            $pq->where('product_name', 'like', "%{$search}%");
                        });
                });
            })
            ->latest();

        $paginated = $query->paginate($request->query('per_page', 15));

        $counts = [
            'all'        => TefaOrder::count(),
            'pending'    => TefaOrder::where('status', 'pending')->count(),
            'paid'       => TefaOrder::where('status', 'paid')->count(),
            'processing' => TefaOrder::where('status', 'processing')->count(),
            'completed'  => TefaOrder::whereIn('status', ['completed', 'done'])->count(),
            'cancelled'  => TefaOrder::where('status', 'cancelled')->count(),
        ];

        return response()->json([
            'data'         => $paginated->items(),
            'current_page' => $paginated->currentPage(),
            'last_page'    => $paginated->lastPage(),
            'per_page'     => $paginated->perPage(),
            'total'        => $paginated->total(),
            'from'         => $paginated->firstItem() ?? 0,
            'to'           => $paginated->lastItem() ?? 0,
            'counts'       => $counts,
        ]);
    }

    public function show(TefaOrder $tefaOrder): JsonResponse
    {
        return response()->json($tefaOrder->load('items.product'));
    }

    public function updateStatus(Request $request, TefaOrder $tefaOrder): JsonResponse
    {
        $data = $request->validate([
            'status' => ['required', 'string', 'in:pending,paid,processing,completed,cancelled,done'],
        ]);

        if ($data['status'] === 'done') {
            $data['status'] = 'completed';
        }

        $tefaOrder->update($data);
        $this->logger->log($request->user()->id, 'status_updated', 'tefa_orders', $tefaOrder->id, "New status: {$data['status']}");

        return response()->json(['message' => 'Status pesanan berhasil diperbarui.', 'order' => $tefaOrder]);
    }
}
