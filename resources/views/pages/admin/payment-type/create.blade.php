<x-layouts.admin>
    <h1 class="text-2xl font-bold mb-4">Tambah Tipe Pembayaran</h1>

    <form method="POST" action="{{ route('admin.payment-types.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block mb-1">Nama</label>
            <input type="text" name="nama" class="input input-bordered w-full" required>
        </div>

        <button class="btn btn-primary">Simpan</button>
        <a href="{{ route('admin.payment-types.index') }}" class="btn">Batal</a>
    </form>
</x-layouts.admin>
