<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    /**
     * Menampilkan daftar alamat pengiriman pengguna.
     */
    public function index()
    {
        $addresses = Address::where('user_id', Auth::id())->get();

        return view('addresses.index', compact('addresses'));
    }

    /**
     * Menampilkan form untuk menambah alamat baru.
     */
    public function create()
    {
        $title = 'checkout';
        $count = Auth::check() ? Auth::user()->carts->count() : 0;
        return view('user.addresses.create',compact('title','count'));
    }

    /**
     * Menyimpan alamat baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'street' => 'required|string|max:255',
        ]);

        Address::create([
            'user_id' => Auth::id(),
            'name' => $request->name,
            'phone_number' => $request->phone,
            'address' => $request->street,
        ]);

        return redirect()->route('checkout')->with('success', 'Alamat berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit alamat.
     */
    public function edit(Address $address)
    {
        // Pastikan hanya pemilik alamat yang bisa mengedit
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        return view('addresses.edit', compact('address'));
    }

    /**
     * Memperbarui data alamat di database.
     */
    public function update(Request $request, Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'postal_code' => 'required|string|max:10',
        ]);

        $address->update($request->all());

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil diperbarui.');
    }

    /**
     * Menghapus alamat dari database.
     */
    public function destroy(Address $address)
    {
        if ($address->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $address->delete();

        return redirect()->route('addresses.index')->with('success', 'Alamat berhasil dihapus.');
    }
}
