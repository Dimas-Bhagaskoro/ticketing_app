<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentType;
use Illuminate\Http\Request;

class PaymentTypeController extends Controller
{
    public function index()
    {
        $paymentTypes = PaymentType::latest()->get();
        return view('pages.admin.payment-type.index', compact('paymentTypes'));
    }

    public function create()
    {
        return view('pages.admin.payment-type.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:payment_types,nama',
        ]);

        PaymentType::create($validated);

        return redirect()
            ->route('admin.payment-types.index')
            ->with('success', 'Tipe pembayaran berhasil ditambahkan');
    }

    public function edit(PaymentType $paymentType)
    {
        return view('pages.admin.payment-type.edit', compact('paymentType'));
    }

    public function update(Request $request, PaymentType $paymentType)
    {
        $validated = $request->validate([
            'nama' => 'required|string|max:255|unique:payment_types,nama,' . $paymentType->id,
        ]);

        $paymentType->update($validated);

        return redirect()
            ->route('admin.payment-types.index')
            ->with('success', 'Tipe pembayaran berhasil diperbarui');
    }

    public function destroy(PaymentType $paymentType)
    {
        $paymentType->delete();

        return redirect()
            ->route('admin.payment-types.index')
            ->with('success', 'Tipe pembayaran berhasil dihapus');
    }
}
