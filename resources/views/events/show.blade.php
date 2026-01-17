<x-layouts.app>
    <section class="max-w-7xl mx-auto py-12 px-6">

        {{-- ===================== BREADCRUMB ===================== --}}
        <nav class="mb-6">
            <div class="breadcrumbs">
                <ul>
                    <li><a href="{{ route('home') }}" class="link link-neutral">Beranda</a></li>
                    <li><a href="#" class="link link-neutral">Event</a></li>
                    <li>{{ $event->judul }}</li>
                </ul>
            </div>
        </nav>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ===================== LEFT CONTENT ===================== --}}
            <div class="lg:col-span-2">
                <div class="card bg-base-100 shadow">

                    {{-- EVENT IMAGE --}}
                    <figure>
                        <img
                            src="{{ $event->gambar
                                ? asset('storage/' . $event->gambar)
                                : 'https://img.daisyui.com/images/stock/photo-1606107557195-0e29a4b5b4aa.webp' }}"
                            alt="{{ $event->judul }}"
                            class="w-full h-96 object-cover"
                        />
                    </figure>

                    <div class="card-body">

                        {{-- EVENT HEADER --}}
                        <div>
                            <h1 class="text-3xl font-extrabold">{{ $event->judul }}</h1>
                            <p class="text-sm text-gray-500 mt-1">
                                {{ \Carbon\Carbon::parse($event->tanggal_waktu)->locale('id')->translatedFormat('d F Y, H:i') }}
                                • 📍 {{ $event->lokasi }}
                            </p>

                            <div class="mt-3 flex gap-2">
                                <span class="badge badge-primary">{{ $event->kategori?->nama ?? 'Tanpa Kategori' }}</span>
                                <span class="badge">{{ $event->user?->name ?? 'Penyelenggara' }}</span>
                            </div>
                        </div>

                        {{-- DESCRIPTION --}}
                        <p class="mt-4 text-gray-700 leading-relaxed">
                            {{ $event->deskripsi }}
                        </p>

                        <div class="divider"></div>

                        {{-- ===================== USER TICKET SELECTION ===================== --}}
                        <h3 class="text-xl font-bold">Pilih Tiket</h3>

                        <div class="mt-4 space-y-4">
                            @forelse($event->tikets as $tiket)
                                <div class="card card-side shadow-sm p-4 items-center">

                                    {{-- TICKET INFO --}}
                                    <div class="flex-1">
                                        <h4 class="font-bold">{{ $tiket->tipe }}</h4>
                                        <p class="text-sm text-gray-500">
                                            Stok: <span id="stock-{{ $tiket->id }}">{{ $tiket->stok }}</span>
                                        </p>
                                        <p class="text-sm mt-2">{{ $tiket->keterangan ?? '-' }}</p>
                                    </div>

                                    {{-- TICKET ACTION --}}
                                    <div class="w-44 text-right">
                                        <div class="text-lg font-bold">
                                            {{ $tiket->harga
                                                ? 'Rp ' . number_format($tiket->harga, 0, ',', '.')
                                                : 'Gratis' }}
                                        </div>

                                        <div class="mt-3 flex items-center justify-end gap-2">
                                            <button class="btn btn-sm btn-outline"
                                                data-action="dec"
                                                data-id="{{ $tiket->id }}">−</button>

                                            <input
                                                id="qty-{{ $tiket->id }}"
                                                type="number"
                                                min="0"
                                                max="{{ $tiket->stok }}"
                                                value="0"
                                                class="input input-bordered w-16 text-center"
                                                data-id="{{ $tiket->id }}"
                                            />

                                            <button class="btn btn-sm btn-outline"
                                                data-action="inc"
                                                data-id="{{ $tiket->id }}">+</button>
                                        </div>

                                        <div class="text-sm text-gray-500 mt-2">
                                            Subtotal:
                                            <span id="subtotal-{{ $tiket->id }}">Rp 0</span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="alert alert-info">Tiket belum tersedia.</div>
                            @endforelse
                        </div>

                    </div>
                </div>
            </div>

            {{-- ===================== SUMMARY ===================== --}}
            <aside class="lg:col-span-1">
                <div class="card sticky top-24 p-4 bg-base-100 shadow">
                    <h4 class="font-bold text-lg">Ringkasan Pembelian</h4>

                    <div class="mt-4">
                        <div class="flex justify-between text-sm text-gray-500">
                            <span>Item</span>
                            <span id="summaryItems">0</span>
                        </div>
                        <div class="flex justify-between text-xl font-bold mt-1">
                            <span>Total</span>
                            <span id="summaryTotal">Rp 0</span>
                        </div>
                    </div>

                    <div class="divider"></div>

                    <div id="selectedList" class="space-y-2 text-sm text-gray-700">
                        <p class="text-gray-500">Belum ada tiket dipilih</p>
                    </div>

                    @auth
                        <button
                            id="checkoutButton"
                            class="btn btn-primary btn-block mt-6 !bg-blue-900 text-white"
                            onclick="openCheckout()"
                            disabled
                        >
                            Checkout
                        </button>
                    @else
                        <a href="{{ route('login') }}" class="btn btn-primary btn-block mt-6 text-white">
                            Login untuk Checkout
                        </a>
                    @endauth
                </div>
            </aside>

        </div>

        {{-- ===================== CHECKOUT MODAL ===================== --}}
        <dialog id="checkout_modal" class="modal">
            <form method="dialog" class="modal-box">
                <h3 class="font-bold text-lg">Konfirmasi Pembelian</h3>

                <div class="mt-4 space-y-2 text-sm">
                    <div id="modalItems">
                        <p class="text-gray-500">Belum ada item.</p>
                    </div>

                    <div class="divider"></div>

                    <div class="flex justify-between font-bold">
                        <span>Total</span>
                        <span id="modalTotal">Rp 0</span>
                    </div>
                </div>

                <div class="modal-action">
                    <button class="btn">Tutup</button>
                    <button
                        type="button"
                        id="confirmCheckout"
                        class="btn btn-primary !bg-blue-900 text-white"
                    >
                        Konfirmasi
                    </button>
                </div>
            </form>
        </dialog>

    </section>

    {{-- ===================== SCRIPT ===================== --}}
    <script>
        (function () {

            const formatRupiah = val =>
                'Rp ' + Number(val).toLocaleString('id-ID');

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

            const summaryItemsEl  = document.getElementById('summaryItems');
            const summaryTotalEl  = document.getElementById('summaryTotal');
            const selectedListEl  = document.getElementById('selectedList');
            const checkoutButton  = document.getElementById('checkoutButton');

            function updateSummary() {
                let totalQty = 0;
                let totalPrice = 0;
                let html = '';

                Object.values(tickets).forEach(t => {
                    const qty = Number(document.getElementById('qty-' + t.id)?.value || 0);
                    if (qty > 0) {
                        totalQty += qty;
                        totalPrice += qty * t.price;
                        html += `
                            <div class="flex justify-between">
                                <span>${t.tipe} x ${qty}</span>
                                <span>${formatRupiah(qty * t.price)}</span>
                            </div>`;
                    }
                });

                summaryItemsEl.textContent = totalQty;
                summaryTotalEl.textContent = formatRupiah(totalPrice);
                selectedListEl.innerHTML  = html || '<p class="text-gray-500">Belum ada tiket dipilih</p>';
                checkoutButton.disabled   = totalQty === 0;
            }

            document.querySelectorAll('[data-action]').forEach(btn => {
                btn.addEventListener('click', e => {
                    const id = e.target.dataset.id;
                    const action = e.target.dataset.action;
                    const input = document.getElementById('qty-' + id);
                    const info = tickets[id];

                    let val = Number(input.value || 0);
                    if (action === 'inc' && val < info.stock) val++;
                    if (action === 'dec' && val > 0) val--;

                    input.value = val;
                    document.getElementById('subtotal-' + id).textContent =
                        formatRupiah(val * info.price);

                    updateSummary();
                });
            });

            updateSummary();
        })();
    </script>
</x-layouts.app>
