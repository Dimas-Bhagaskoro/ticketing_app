<x-layouts.admin>
    <h1 class="text-2xl font-bold mb-4">Tipe Pembayaran</h1>

    <a href="{{ route('admin.payment-types.create') }}" class="btn btn-primary mb-4">
        Tambah Tipe Pembayaran
    </a>

    <table class="table w-full">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach($paymentTypes as $i => $type)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $type->nama }}</td>
                <td class="flex gap-2">
                    <a href="{{ route('admin.payment-types.edit', $type) }}" class="btn btn-sm btn-warning">Edit</a>

                    <form action="{{ route('admin.payment-types.destroy', $type) }}" method="POST">
                        @csrf
                        @method('DELETE')
                        <button class="btn btn-sm btn-error" onclick="return confirm('Hapus data?')">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</x-layouts.admin>
