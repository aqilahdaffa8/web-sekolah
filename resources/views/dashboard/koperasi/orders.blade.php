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
<div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-4 mb-6">
    @foreach([
        ['id'=>'ord-pending',    'status'=>'pending',    'label'=>'Menunggu',   'color'=>'bg-warning'],
        ['id'=>'ord-paid',       'status'=>'paid',       'label'=>'Dibayar',    'color'=>'bg-info'],
        ['id'=>'ord-processing', 'status'=>'processing', 'label'=>'Diproses',   'color'=>'bg-purple-500'],
        ['id'=>'ord-done',       'status'=>'completed',  'label'=>'Selesai',    'color'=>'bg-success'],
        ['id'=>'ord-cancel',     'status'=>'cancelled',  'label'=>'Dibatalkan', 'color'=>'bg-danger'],
    ] as $s)
    <div class="card p-5 cursor-pointer hover:shadow-md transition-all group border-2 border-transparent order-stat-card" data-status="{{ $s['status'] }}" onclick="setOrderStatusFilter('{{ $s['status'] }}')">
        <div class="w-8 h-8 {{ $s['color'] }} rounded-lg mb-3 shadow-xs group-hover:scale-105 transition-transform"></div>
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
            <button data-modal-close class="btn btn-ghost">Tutup</button>
            <select id="order-status-select" class="form-select w-auto text-sm py-1.5">
                <option value="pending">Pending</option>
                <option value="paid">Dibayar</option>
                <option value="processing">Diproses</option>
                <option value="completed">Selesai</option>
                <option value="cancelled">Dibatalkan</option>
            </select>
            <button onclick="updateOrderStatus()" class="btn btn-primary btn-sm">Update Status</button>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script type="module">
let currentOrderId = null;

function updateActiveStatCard(selectedStatus) {
    document.querySelectorAll('.order-stat-card').forEach(card => {
        if (selectedStatus && card.dataset.status === selectedStatus) {
            card.classList.remove('border-transparent');
            card.classList.add('border-brand-500', 'bg-brand-50/20');
        } else {
            card.classList.remove('border-brand-500', 'bg-brand-50/20');
            card.classList.add('border-transparent');
        }
    });
}

window.setOrderStatusFilter = (status) => {
    const select = document.getElementById('status-filter');
    if (select) {
        if (select.value === status) {
            select.value = '';
        } else {
            select.value = status;
        }
        updateActiveStatCard(select.value);
        loadOrders(1);
    }
};

async function loadOrders(page = 1) {
    const search = document.getElementById('order-search').value;
    const status = document.getElementById('status-filter').value;
    updateActiveStatCard(status);
    try {
        const res = await window.koperasiApi.orders({ page, per_page: 15, search, status });
        const orders = res.data || [];
        renderOrders(orders);
        window.utils.renderPagination('orders-pagination', res.meta, loadOrders);

        // Count by status
        if (res.counts) {
            const elPending = document.getElementById('ord-pending');
            const elPaid = document.getElementById('ord-paid');
            const elProcessing = document.getElementById('ord-processing');
            const elDone = document.getElementById('ord-done');
            const elCancel = document.getElementById('ord-cancel');

            if (elPending) elPending.textContent = res.counts.pending ?? 0;
            if (elPaid) elPaid.textContent = res.counts.paid ?? 0;
            if (elProcessing) elProcessing.textContent = res.counts.processing ?? 0;
            if (elDone) elDone.textContent = res.counts.completed ?? 0;
            if (elCancel) elCancel.textContent = res.counts.cancelled ?? 0;
        } else {
            const countMap = {
                'ord-pending': 'pending',
                'ord-paid': 'paid',
                'ord-processing': 'processing',
                'ord-done': ['completed', 'done'],
                'ord-cancel': 'cancelled'
            };
            Object.entries(countMap).forEach(([id, targetStatus]) => {
                const el = document.getElementById(id);
                if (el) {
                    el.textContent = orders.filter(o => 
                        Array.isArray(targetStatus) ? targetStatus.includes(o.status) : o.status === targetStatus
                    ).length;
                }
            });
        }
    } catch (err) { window.toast.apiError(err); }
}

function renderOrders(orders) {
    document.getElementById('orders-tbody').innerHTML = orders.length ? orders.map(o => {
        const firstItem = o.items?.[0] || {};
        const product = firstItem.product || o.product || {};
        const quantity = firstItem.quantity ?? o.quantity ?? 0;

        return `
        <tr class="cursor-pointer hover:bg-brand-50/40 transition-colors" onclick="viewOrder(${JSON.stringify(o).replace(/"/g,'&quot;')})">
            <td class="font-medium text-gray-900">
                <div class="flex items-center gap-2.5">
                    <div class="w-8 h-8 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center flex-shrink-0 text-slate-400 overflow-hidden shadow-xs">
                        <svg class="w-5 h-5 text-slate-400 mt-1" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <span>${o.buyer_name || o.customer_name || 'Pelanggan'}</span>
                </div>
            </td>
            <td class="text-gray-600">${product.product_name ?? product.name ?? '-'}</td>
            <td><span class="font-semibold text-gray-800">${quantity}</span></td>
            <td class="font-semibold text-brand-900">${window.utils.formatCurrency(o.total_price)}</td>
            <td>${window.utils.statusBadge(o.status)}</td>
            <td class="text-gray-400 text-xs">${window.utils.formatDate(o.created_at, {withTime: true})}</td>
            <td class="text-right">
                <button onclick="event.stopPropagation(); viewOrder(${JSON.stringify(o).replace(/"/g,'&quot;')})"
                        class="btn-table-view text-xs py-1 px-3">Detail</button>
            </td>
        </tr>
    `;
    }).join('') : '<tr><td colspan="7" class="text-center py-12 text-gray-400">Tidak ada pesanan.</td></tr>';
}

window.viewOrder = (order) => {
    if (typeof order === 'string') order = JSON.parse(order);
    currentOrderId = order.id;
    document.getElementById('order-status-select').value = order.status === 'done' ? 'completed' : order.status;
    const firstItem = order.items?.[0] || {};
    const product = firstItem.product || order.product || {};
    const prodName = product.product_name ?? product.name ?? '-';
    document.getElementById('order-detail-body').innerHTML = `
        <div class="space-y-4">
            <div class="flex items-center gap-3 pb-3 border-b border-gray-100">
                <div class="w-11 h-11 rounded-full bg-slate-100 border border-slate-200 flex items-center justify-center flex-shrink-0 text-slate-400 overflow-hidden shadow-xs">
                    <svg class="w-7 h-7 text-slate-400 mt-1" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div>
                    <p class="font-bold text-gray-900">${order.buyer_name || order.customer_name || 'Pelanggan'}</p>
                    <p class="text-xs text-gray-500">${order.buyer_contact || order.customer_phone || '-'}</p>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-3 text-sm">
                <div><p class="text-gray-400">Produk</p><p class="font-semibold">${prodName}</p></div>
                <div><p class="text-gray-400">Jumlah</p><p class="font-semibold">${firstItem.quantity ?? order.quantity ?? 0} ${product.unit || 'pcs'}</p></div>
                <div><p class="text-gray-400">Total</p><p class="font-black text-brand-700 text-lg">${window.utils.formatCurrency(order.total_price)}</p></div>
                <div><p class="text-gray-400">Status</p>${window.utils.statusBadge(order.status)}</div>
            </div>
            <div>
                <p class="text-gray-400 text-sm font-medium flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-brand-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                    Alamat Pengiriman
                </p>
                <div class="mt-1 bg-gray-50 border border-gray-100 rounded-lg p-2.5 text-sm font-medium text-gray-800 leading-relaxed">
                    ${order.delivery_address ? order.delivery_address : '<span class="text-gray-400 italic">Alamat belum dicantumkan</span>'}
                </div>
            </div>
            ${order.notes ? `
            <div>
                <p class="text-gray-400 text-sm font-medium flex items-center gap-1.5">
                    <svg class="w-4 h-4 text-amber-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z" />
                    </svg>
                    Catatan Pesanan
                </p>
                <div class="mt-1 bg-amber-50/50 border border-amber-100 rounded-lg p-2.5 text-sm font-medium text-gray-800 leading-relaxed">
                    ${order.notes}
                </div>
            </div>` : ''}
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

