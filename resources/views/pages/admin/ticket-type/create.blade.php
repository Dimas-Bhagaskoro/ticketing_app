<x-layouts.admin>
    <h1 class="text-2xl font-bold mb-4">Tambah Tipe Tiket</h1>

    <form method="POST" action="{{ route('admin.ticket-types.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block mb-1 font-semibold">Nama Tipe Tiket</label>
            <input
                type="text"
                name="nama"
                class="input input-bordered w-full"
                required
            >
        </div>

        <div class="flex gap-2">
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('admin.ticket-types.index') }}" class="btn">Batal</a>
        </div>
    </form>
</x-layouts.admin>
