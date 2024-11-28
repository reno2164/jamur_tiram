<?php

namespace App\Http\Controllers;

use App\Models\Address;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Log;

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
        $title = 'tambah alamat';
        $count = Auth::check() ? Auth::user()->carts->count() : 0;
        return view('user.addresses.create', compact('title', 'count'));
    }

    /**
     * Menyimpan alamat baru ke database.
     */
    public function store(Request $request)
    {

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone' => 'required|numeric|max_digits:20',
            'street' => 'required|string|max:255',
        ]);

        Address::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'phone_number' => $validated['phone'],
            'address' => $validated['street'],
            'is_default' => $request->has('is_default'),
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
        $title = 'Ubah Alamat';
        $count = Auth::check() ? Auth::user()->carts->count() : 0;

        return view('user.addresses.edit', compact('address','title','count'));
    }

    /**
     * Memperbarui data alamat di database.
     */
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|numeric|max_digits:20',
            'address' => 'required|string|max:255',
        ]);

        $address = Address::findOrFail($id);
        $address->update([
            'name' => $validated['name'],
            'phone_number' => $validated['phone_number'],
            'address' => $validated['address'],
            'is_default' => $request->has('is_default'),
        ]);

        return redirect()->route('checkout')->with('success', 'Alamat berhasil diperbarui.');
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
