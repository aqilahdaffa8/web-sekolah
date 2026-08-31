@extends('layouts.dashboard')

@section('title', 'Pesanan TeFA')

@section('content')
<div class="flex justify-between items-center mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Pesanan TeFA</h1>
        <p class="text-sm text-gray-500 mt-1">Kelola data pesanan produk TeFA (Teaching Factory).</p>
    </div>
</div>

<div class="card p-0 overflow-hidden mb-6">
    <div class="p-4 border-b border-gray-100 flex gap-4 bg-gray-50">
        <select id="status-filter" class="form-input w-48">
            <option value="">Semua Status</option>
            <option value="pending">Pending</option>
            <option value="paid">Paid</option>
            <option value="completed">Completed</option>
        </select>
    </div>

    <x-ui.table id="orders-table" :headers="['Kode Pesanan', 'Pembeli', 'Kontak', 'Total', 'Status', 'Aksi']">
        {{-- Akan diisi via JS --}}
    </x-ui.table>
    
    <div id="pagination-container" class="px-4 py-3 border-t border-gray-100 flex justify-between items-center text-sm text-gray-600 hidden">
        <span id="pagination-info"></span>
        <div class="flex gap-2" id="pagination-buttons"></div>
    </div>
</div>

<x-ui.modal id="order-modal" title="Detail Pesanan">
    <div id="order-details" class="mb-6 space-y-3">
        <!-- Akan diisi dengan detail order dan items via JS -->
    </div>

    <form id="order-status-form" class="border-t pt-4">
        <input type="hidden" id="order_id" name="order_id">
        <div class="flex items-end gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Update Status</label>
                <select id="order_status" name="order_status" class="form-input w-full" required>
                    <option value="pending">Pending</option>
                    <option value="paid">Paid</option>
                    <option value="completed">Completed</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary" id="btn-update-status">Update Status</button>
        </div>
    </form>
    
    <div class="mt-6 flex justify-end gap-3">
        <button type="button" class="btn btn-secondary" data-modal-close="order-modal">Tutup</button>
    </div>
</x-ui.modal>

<script type="module">
    document.addEventListener('DOMContentLoaded', () => {
        const tbody = document.getElementById('orders-table');
        const loading = document.getElementById('orders-table-loading');
        const empty = document.getElementById('orders-table-empty');
        const modal = document.getElementById('order-modal');
        const statusForm = document.getElementById('order-status-form');
        const statusFilter = document.getElementById('status-filter');
        const detailsContainer = document.getElementById('order-details');
        
        let orders = [];

        window.openDetailModal = (id) => {
            const o = orders.find(x => x.id === id);
            if (!o) return;

            document.getElementById('order_id').value = o.id;
            document.getElementById('order_status').value = o.status;

            let itemsHtml = '<ul class="divide-y divide-gray-100 border rounded bg-gray-50">';
            if (o.items && o.items.length > 0) {
                o.items.forEach(item => {
                    const prodName = item.product ? item.product.product_name : 'Produk Dihapus';
                    const itemPrice = item.subtotal / item.quantity;
                    itemsHtml += `
                        <li class="p-3 flex justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-900">${prodName}</p>
                                <p class="text-xs text-gray-500">${item.quantity} x Rp ${new Intl.NumberFormat('id-ID').format(itemPrice)}</p>
                            </div>
                            <span class="text-sm font-bold text-gray-900">Rp ${new Intl.NumberFormat('id-ID').format(item.subtotal)}</span>
                        </li>
                    `;
                });
            } else {
                itemsHtml += '<li class="p-3 text-sm text-gray-500 text-center">Tidak ada item</li>';
            }
            itemsHtml += '</ul>';

            detailsContainer.innerHTML = `
                <div class="grid grid-cols-2 gap-4 text-sm mb-4">
                    <div>
                        <span class="block text-gray-500">Kode Pesanan</span>
                        <span class="font-medium">${o.order_code}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500">Total Harga</span>
                        <span class="font-bold text-lg text-blue-600">Rp ${new Intl.NumberFormat('id-ID').format(o.total_price)}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500">Pembeli</span>
                        <span class="font-medium">${o.buyer_name}</span>
                    </div>
                    <div>
                        <span class="block text-gray-500">Kontak</span>
                        <span class="font-medium">${o.buyer_contact || '-'}</span>
                    </div>
                </div>
                <h4 class="font-semibold text-gray-800 mb-2">Item Pesanan</h4>
                ${itemsHtml}
            `;

            modal.classList.remove('hidden');
        };

        const loadOrders = async () => {
            const status = statusFilter.value;

            if (tbody) tbody.innerHTML = '';
            if (loading) loading.classList.remove('hidden');
            if (empty) empty.classList.add('hidden');

            try {
                let url = `/koperasi/orders`;
                if (status) url += `?status=${encodeURIComponent(status)}`;

                const response = await window.api.get(url);
                orders = response.data || response;

                if (loading) loading.classList.add('hidden');

                if (!orders || orders.length === 0) {
                    if (empty) empty.classList.remove('hidden');
                    return;
                }

                let rows = '';
                orders.forEach(o => {
                    const badgeClass = o.status === 'completed' ? 'badge-completed' : 
                                       o.status === 'paid' ? 'badge-active' : 'badge-open';
                    
                    rows += `
                        <tr>
                            <td class="font-medium text-gray-900">${o.order_code}</td>
                            <td class="text-gray-600">${o.buyer_name}</td>
                            <td class="text-gray-600">${o.buyer_contact || '-'}</td>
                            <td class="font-bold text-gray-900">Rp ${new Intl.NumberFormat('id-ID').format(o.total_price)}</td>
                            <td><span class="badge ${badgeClass} uppercase text-xs">${o.status}</span></td>
                            <td>
                                <button onclick="openDetailModal(${o.id})" class="text-blue-600 hover:text-blue-800">Detail & Status</button>
                            </td>
                        </tr>
                    `;
                });
                if (tbody) tbody.innerHTML = rows;

            } catch (error) {
                if (loading) loading.classList.add('hidden');
                console.error(error);
                window.showToast('Gagal memuat pesanan', 'error');
            }
        };

        statusForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = document.getElementById('btn-update-status');
            btn.disabled = true;
            btn.textContent = 'Menyimpan...';

            const id = document.getElementById('order_id').value;
            const newStatus = document.getElementById('order_status').value;

            try {
                await window.api.patch(`/koperasi/orders/${id}/status`, { status: newStatus });
                window.showToast('Status pesanan berhasil diupdate', 'success');
                modal.classList.add('hidden');
                loadOrders();
            } catch (error) {
                console.error(error);
                window.showToast('Gagal mengupdate status', 'error');
            } finally {
                btn.disabled = false;
                btn.textContent = 'Update Status';
            }
        });

        statusFilter.addEventListener('change', loadOrders);

        loadOrders();
    });
</script>
@endsection
