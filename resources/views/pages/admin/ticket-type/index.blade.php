<x-layouts.admin>
    <h1 class="text-2xl font-bold mb-4">Tipe Tiket</h1>

    <a href="{{ route('admin.ticket-types.create') }}"
       class="btn btn-primary mb-4">
        Tambah Tipe Tiket
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
            @forelse ($ticketTypes as $type)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $type->nama }}</td>
                    <td class="flex gap-2">
                        <a href="{{ route('admin.ticket-types.edit', $type) }}"
                           class="btn btn-sm btn-warning">Edit</a>

                        <form action="{{ route('admin.ticket-types.destroy', $type) }}"
                              method="POST"
                              onsubmit="return confirm('Yakin hapus?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-error">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">Belum ada tipe tiket</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</x-layouts.admin>
