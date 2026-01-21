<x-layouts.app>
<section class="max-w-7xl mx-auto py-12 px-6">

    {{-- BREADCRUMB --}}
    <nav class="mb-6">
        <div class="breadcrumbs">
            <ul>
                <li><a href="{{ route('home') }}">Beranda</a></li>
                <li>Event</li>
                <li>{{ $event->judul }}</li>
            </ul>
        </div>
    </nav>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT --}}
        <div class="lg:col-span-2">
            <div class="card bg-base-100 shadow">
                <figure>
                    <img
                        src="{{ $event->gambar
                            ? asset('storage/'.$event->gambar)
                            : 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp' }}"
                        class="w-full h-96 object-cover"
                    >
                </figure>

                <div class="card-body">
                    <h1 class="text-3xl font-bold">{{ $event->judul }}</h1>
                    <p class="text-gray-500">
                        {{ \Carbon\Carbon::parse($event->tanggal_waktu)->translatedFormat('d F Y, H:i') }}
                        • 📍 {{ $event->lokasi }}
                    </p>

                    <p class="mt-4">{{ $event->deskripsi }}</p>

                    <div class="divider"></div>

                    <h3 class="text-xl font-bold">Pilih Tiket</h3>

                    <div class="space-y-4 mt-4">
                        @foreach($event->tikets as $tiket)
                        <div class="flex justify-between items-center border p-4 rounded">
                            <div>
                                <p class="font-semibold">{{ $tiket->tipe }}</p>
                                <p class="text-sm text-gray-500">Stok: {{ $tiket->stok }}</p>
                            </div>

                            <div class="text-right">
                                <p class="font-bold">
                                    {{ $tiket->harga ? 'Rp '.number_format($tiket->harga) : 'Gratis' }}
                                </p>

                                <div class="flex items-center gap-2 mt-2">
                                    <button
                                        type="button"
                                        class="btn btn-sm"
                                        data-action="dec"
                                        data-id="{{ $tiket->id }}">−</button>

                                    <input
                                        id="qty-{{ $tiket->id }}"
                                        type="number"
                                        value="0"
                                        min="0"
                                        max="{{ $tiket->stok }}"
                                        class="input input-bordered w-16 text-center"
                                    >

                                    <button
                                        type="button"
                                        class="btn btn-sm"
                                        data-action="inc"
                                        data-id="{{ $tiket->id }}">+</button>
                                </div>

                                <p class="text-sm mt-1">
                                    Subtotal:
                                    <span id="subtotal-{{ $tiket->id }}">Rp 0</span>
                                </p>
                            </div>
                        </div>
                        @endforeach
                    </div>

                </div>
            </div>
        </div>

        {{-- RIGHT --}}
        <aside>
            <div class="card bg-base-100 p-4 shadow sticky top-24">
                <h4 class="font-bold text-lg">Ringkasan Pembelian</h4>

                <div class="mt-4">
                    <div class="flex justify-between">
                        <span>Item</span>
                        <span id="summaryItems">0</span>
                    </div>
                    <div class="flex justify-between text-xl font-bold">
                        <span>Total</span>
                        <span id="summaryTotal">Rp 0</span>
                    </div>
                </div>

                <div class="divider"></div>

                <div id="selectedList" class="text-sm text-gray-600">
                    Belum ada tiket dipilih
                </div>

                @auth
                <button
                    id="checkoutButton"
                    class="btn btn-primary w-full mt-4"
                    onclick="openCheckout()"
                    disabled
                >
                    Checkout
                </button>
                @else
                <a href="{{ route('login') }}" class="btn btn-primary w-full mt-4">
                    Login untuk Checkout
                </a>
                @endauth
            </div>
        </aside>

    </div>

    {{-- MODAL --}}
    <dialog id="checkout_modal" class="modal">
        <form method="dialog" class="modal-box">
            <h3 class="font-bold text-lg">Konfirmasi Pembelian</h3>

            <div id="modalItems" class="mt-4 text-sm"></div>

            <div class="divider"></div>

            <div class="flex justify-between font-bold">
                <span>Total</span>
                <span id="modalTotal">Rp 0</span>
            </div>

            <div class="modal-action">
                <button class="btn">Tutup</button>
                <button type="button" id="confirmCheckout" class="btn btn-primary">
                    Konfirmasi
                </button>
            </div>
        </form>
    </dialog>

</section>

<script>
document.addEventListener('DOMContentLoaded', function () {

    const format = v => 'Rp ' + Number(v).toLocaleString('id-ID');

    const tickets = {
        @foreach($event->tikets as $tiket)
        {{ $tiket->id }}: {
            id: {{ $tiket->id }},
            price: {{ $tiket->harga ?? 0 }},
            stock: {{ $tiket->stok }},
            tipe: "{{ e($tiket->tipe) }}"
        },
        @endforeach
    };

    const summaryItems  = document.getElementById('summaryItems');
    const summaryTotal  = document.getElementById('summaryTotal');
    const selectedList  = document.getElementById('selectedList');
    const checkoutBtn   = document.getElementById('checkoutButton');

    function updateSummary() {
        let qty = 0, total = 0, html = '';

        Object.values(tickets).forEach(t => {
            const q = Number(document.getElementById('qty-'+t.id).value || 0);
            if (q > 0) {
                qty += q;
                total += q * t.price;
                html += `<div>${t.tipe} x ${q} = ${format(q * t.price)}</div>`;
            }
        });

        summaryItems.textContent = qty;
        summaryTotal.textContent = format(total);
        selectedList.innerHTML = html || 'Belum ada tiket dipilih';

        if (checkoutBtn) checkoutBtn.disabled = qty === 0;
    }

    document.querySelectorAll('[data-action]').forEach(btn => {
        btn.addEventListener('click', e => {
            const id = e.currentTarget.dataset.id;
            const action = e.currentTarget.dataset.action;
            const input = document.getElementById('qty-'+id);

            let val = Number(input.value || 0);
            if (action === 'inc' && val < tickets[id].stock) val++;
            if (action === 'dec' && val > 0) val--;

            input.value = val;
            document.getElementById('subtotal-'+id).textContent =
                format(val * tickets[id].price);

            updateSummary();
        });
    });

    window.openCheckout = function () {
        document.getElementById('modalItems').innerHTML = selectedList.innerHTML;
        document.getElementById('modalTotal').textContent = summaryTotal.textContent;
        document.getElementById('checkout_modal').showModal();
    };

    document.getElementById('confirmCheckout').addEventListener('click', async () => {
        const items = [];
        Object.values(tickets).forEach(t => {
            const q = Number(document.getElementById('qty-'+t.id).value || 0);
            if (q > 0) items.push({ tiket_id: t.id, jumlah: q });
        });

        if (items.length === 0) {
            alert('Tidak ada tiket dipilih');
            return;
        }

        const res = await fetch("{{ route('orders.store') }}", {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({ event_id: {{ $event->id }}, items })
        });

        if (res.ok) {
            window.location.href = "{{ route('orders.index') }}";
        } else {
            alert('Gagal membuat pesanan');
        }
    });

    updateSummary();
});
</script>
</x-layouts.app>
