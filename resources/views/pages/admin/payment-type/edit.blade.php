<x-layouts.admin>
    <h1 class="text-2xl font-bold mb-4">Edit Tipe Pembayaran</h1>

    <form method="POST" action="{{ route('admin.payment-types.update', $paymentType) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block mb-1">Nama</label>
            <input type="text" name="nama"
                value="{{ old('nama', $paymentType->nama) }}"
                class="input input-bordered w-full" required>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('admin.payment-types.index') }}" class="btn">Batal</a>
    </form>
</x-layouts.admin>