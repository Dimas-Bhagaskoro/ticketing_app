<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TicketType;
use Illuminate\Http\Request;

class TicketTypeController extends Controller
{
    public function index()
    {
        $ticketTypes = TicketType::latest()->get();
        return view('pages.admin.ticket-type.index', compact('ticketTypes'));
    }

    public function create()
    {
        return view('pages.admin.ticket-type.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:ticket_types,nama',
        ]);

        TicketType::create($validated);

        return redirect()
            ->route('admin.ticket-types.index')
            ->with('success', 'Tipe tiket berhasil ditambahkan');
    }

    public function edit(TicketType $ticketType)
    {
        return view('pages.admin.ticket-type.edit', compact('ticketType'));
    }

    public function update(Request $request, TicketType $ticketType)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:ticket_types,nama,' . $ticketType->id,
        ]);

        $ticketType->update($validated);

        return redirect()
            ->route('admin.ticket-types.index')
            ->with('success', 'Tipe tiket berhasil diperbarui');
    }

    public function destroy(TicketType $ticketType)
    {
        $ticketType->delete();

        return redirect()
            ->route('admin.ticket-types.index')
            ->with('success', 'Tipe tiket berhasil dihapus');
    }
}
