@extends('layouts.dashboard')
@section('title', 'Pesanan Masuk')
@section('content')

<div class="flex items-center justify-between mb-6 flex-wrap gap-4">
    <div>
        <h1 class="text-2xl font-black text-gray-900">Pesanan Masuk</h1>
        <p class="text-gray-500 mt-1">Kelola semua pesanan produk TeFA dan koperasi.</p>
    </div>
</div>

{{-- Stat cards --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    @foreach([
        ['id'=>'ord-pending',  'label'=>'Menunggu', 'color'=>'bg-warning'],
        ['id'=>'ord-paid',     'label'=>'Dibayar',  'color'=>'bg-info'],
        ['id'=>'ord-done',     'label'=>'Selesai',  'color'=>'bg-success'],
        ['id'=>'ord-cancel',   'label'=>'Dibatalkan','color'=>'bg-danger'],
    ] as $s)
    <div class="card p-5">
        <div class="w-8 h-8 {{ $s['color'] }} rounded-lg mb-3"></div>
        <p class="text-2xl font-black text-gray-900" id="{{ $s['id'] }}">—</p>
        <p class="text-sm text-gray-500">{{ $s['label'] }}</p>
    </div>
    @endforeach
</div>

{{-- Filter --}}
<div class="card mb-5 p-4 flex flex-wrap gap-3">
    <div class="relative flex-1 min-w-[200px]">
        <svg class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
            <path d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z"/>
        </svg>
        <input type="search" id="order-search" class="form-input pl-9 py-2 text-sm" placeholder="Cari nama/produk...">
    </div>
    <select id="status-filter" class="form-select w-auto text-sm py-2">
        <option value="">Semua Status</option>
        <option value="pending">Pending</option>
        <option value="paid">Dibayar</option>
        <option value="processing">Diproses</option>
        <option value="completed">Selesai</option>
        <option value="cancelled">Dibatalkan</option>
    </select>
</div>

{{-- Table --}}
<div class="card">
    <div class="overflow-x-auto">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Pelanggan</th>
                    <th>Produk</th>
                    <th>Qty</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Waktu</th>
                    <th class="text-right">Aksi</th>
                </tr>
            </thead>
            <tbody id="orders-tbody">
                @for($i=0;$i<5;$i++)
                <tr>
                    @for($j=0;$j<7;$j++)<td><div class="skeleton h-4 w-20 rounded"></div></td>@endfor
                </tr>
                @endfor
            </tbody>
        </table>
    </div>
    <div id="orders-pagination" class="p-4 border-t border-gray-100"></div>
</div>

{{-- Order Detail Modal --}}
<div id="order-detail-modal" data-modal class="modal-overlay hidden">
    <div class="modal-box max-w-xl">
        <div class="modal-header">
            <h3 class="text-lg font-bold text-gray-900">Detail Pesanan</h3>
            <button data-modal-close class="btn-icon text-gray-400">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <div class="modal-body" id="order-detail-body"></div>
        <div class="modal-footer">
            <button data-modal-close class="btn-ghost">Tutup</button>
            <select id="order-status-select" class="form-select w-auto text-sm py-1.5">
                <option value="pending">Pending</option>
                <option value="paid">Dibayar</option>
                <option value="processing">Diproses</option>
                <option value="completed">Selesai</option>
                <option value="cancelled">Dibatalkan</option>
            </select>
            <button onclick="updateOrderStatus()" class="btn-primary btn-sm">Update Status</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
let currentOrderId = null;

async function loadOrders(page = 1) {
    const search = document.getElementById('order-search').value;
    const status = document.getElementById('status-filter').value;
    try {
        const res = await window.koperasiApi.orders({ page, per_page: 15, search, status });
        const orders = res.data || [];
        renderOrders(orders);
        window.utils.renderPagination('orders-pagination', res.meta, loadOrders);

        // Count by status
        ['pending','paid','completed','cancelled'].forEach((s, i) => {
            const el = document.getElementById(['ord-pending','ord-paid','ord-done','ord-cancel'][i]);
            if (el) el.textContent = orders.filter(o => o.status === s).length;
        });
    } catch (err) { window.toast.apiError(err); }
}

function renderOrders(orders) {
    document.getElementById('orders-tbody').innerHTML = orders.length ? orders.map(o => `
        <tr class="cursor-pointer hover:bg-brand-50/40 transition-colors" onclick="viewOrder(${JSON.stringify(o).replace(/"/g,'&quot;')})">
            <td class="font-medium text-gray-900">${o.customer_name}</td>
            <td class="text-gray-600">${o.product?.product_name ?? o.product?.name ?? '-'}</td>
            <td><span class="font-semibold text-gray-800">${o.quantity}</span></td>
            <td class="font-semibold text-brand-900">${window.utils.formatCurrency(o.total_price)}</td>
            <td>${window.utils.statusBadge(o.status)}</td>
            <td class="text-gray-400 text-xs">${window.utils.formatDate(o.created_at, {withTime: true})}</td>
            <td class="text-right">
                <button onclick="event.stopPropagation(); viewOrder(${JSON.stringify(o).replace(/"/g,'&quot;')})"
                        class="btn-table-view text-xs py-1 px-3">Detail</button>
            </td>
        </tr>
    `).join('') : '<tr><td colspan="7" class="text-center py-12 text-gray-400">Tidak ada pesanan.</td></tr>';
}

window.viewOrder = (order) => {
    if (typeof order === 'string') order = JSON.parse(order);
    currentOrderId = order.id;
    document.getElementById('order-status-select').value = order.status;
    const prodName = order.product?.product_name ?? order.product?.name ?? '-';
    document.getElementById('order-detail-body').innerHTML = `
        <div class="space-y-4">
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div><p class="text-gray-400">Pelanggan</p><p class="font-semibold">${order.customer_name}</p></div>
                <div><p class="text-gray-400">No. HP</p><p class="font-semibold">${order.customer_phone}</p></div>
                <div><p class="text-gray-400">Produk</p><p class="font-semibold">${prodName}</p></div>
                <div><p class="text-gray-400">Jumlah</p><p class="font-semibold">${order.quantity} ${order.product?.unit || 'pcs'}</p></div>
                <div><p class="text-gray-400">Total</p><p class="font-black text-brand-700 text-lg">${window.utils.formatCurrency(order.total_price)}</p></div>
                <div><p class="text-gray-400">Status</p>${window.utils.statusBadge(order.status)}</div>
            </div>
            <div><p class="text-gray-400 text-sm">Alamat Pengiriman</p><p class="text-sm font-medium mt-1">${order.delivery_address || '-'}</p></div>
            ${order.notes ? `<div><p class="text-gray-400 text-sm">Catatan</p><p class="text-sm mt-1">${order.notes}</p></div>` : ''}
        </div>
    `;
    window.openModal(document.getElementById('order-detail-modal'));
};

window.updateOrderStatus = async () => {
    const status = document.getElementById('order-status-select').value;
    try {
        await window.koperasiApi.updateStatus(currentOrderId, { status });
        window.toast.success('Status pesanan diperbarui.');
        window.closeModal(document.getElementById('order-detail-modal'));
        loadOrders();
    } catch (err) { window.toast.apiError(err); }
};

document.getElementById('order-search').addEventListener('input', window.utils.debounce(() => loadOrders(1), 400));
document.getElementById('status-filter').addEventListener('change', () => loadOrders(1));

loadOrders();
</script>
@endpush
