<?php

namespace App\Http\Controllers;

//import model product

use Surfsidemedia\Shoppingcart\Facades\Cart;
use App\Models\Product;

//import return type View
use Illuminate\View\View;

//import return type redirectResponse
use Illuminate\Http\Request;

//import Http Request
use Illuminate\Http\RedirectResponse;

//import Facades Storage
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * index
     *
     * @return void
     */
    public function index(Request $request): View
{
    // Ambil kata kunci pencarian
    $search = $request->input('search', '');

    // Query produk dengan filter pencarian
    $products = Product::query()
        ->where('title', 'like', '%' . $search . '%')
        ->orWhere('description', 'like', '%' . $search . '%')
        ->latest()
        ->paginate(10);

    // Produk dengan stok keluar >= 5
    $best = Product::where('qty_out', '>=', 5)->get();

    // Kirim data ke view
    return view(
        'admin.page.product',
        [
            'title' => 'Halaman Products',
            'name' => 'Produk',
            'search' => $search, // Untuk mempertahankan nilai input pencarian
        ],
        compact('products', 'best')
    );
}


    /**
     * create
     *
     * @return View
     */
    public function create(): View
    {
        return view('admin.page.products.create', ['title' => 'halaman Products', 'name' => 'create']);
    }

    /**
     * store
     *
     * @param  mixed $request
     * @return RedirectResponse
     */
    public function store(Request $request): RedirectResponse
    {
        //validate form
        $request->validate([
            'image'         => 'required|image|mimes:jpeg,jpg,png|max:2048',
            'title'         => 'required',
            'description'   => 'required|min:10',
            'price'         => 'required|numeric|min:0,1',
            'stok'         => 'required|numeric|min:0,1'
        ]);

        //upload image
        $image = $request->file('image');
        $image->storeAs('public/products', $image->hashName());

        //create product
        Product::create([
            'image'         => $image->hashName(),
            'title'         => $request->title,
            'description'   => $request->description,
            'price'         => $request->price,
            'stok'         => $request->stok
        ]);

        //redirect to index
        return redirect()->route('product.index')->with(['success' => 'Data Berhasil Disimpan!']);
    }

    /**
     * show
     *
     * @param  mixed $id
     * @return View
     */
    public function show(string $id): View
    {
        //get product by ID

        $product = Product::findOrFail($id);

        //render view with product
        return view('admin.page.products.show', ['title' => 'halaman Show', 'name' => 'Show'], compact('product'));
    }

    /**
     * edit
     *
     * @param  mixed $id
     * @return View
     */
    public function edit(string $id): View
    {
        //get product by ID
        $product = Product::findOrFail($id);

        //render view with product
        return view('admin.page.products.edit', ['title' => 'halaman edit', 'name' => 'edit'], compact('product'));
    }

    /**
     * update
     *
     * @param  mixed $request
     * @param  mixed $id
     * @return RedirectResponse
     */
    public function update(Request $request, $id): RedirectResponse
    {
        //validate form
        $request->validate([
            'image'         => 'image|mimes:jpeg,jpg,png|max:2048',
            'title'         => 'required|min:5',
            'description'   => 'required|min:10',
            'price'         => 'required|numeric|min:1',
            'stok'         => 'required|numeric|min:1'
        ]);

        //get product by ID
        $product = Product::findOrFail($id);

        //check if image is uploaded
        if ($request->hasFile('image')) {

            //upload new image
            $image = $request->file('image');
            $image->storeAs('public/products', $image->hashName());

            //delete old image
            Storage::delete('public/products/' . $product->image);

            //update product with new image
            $product->update([
                'image'         => $image->hashName(),
                'title'         => $request->title,
                'description'   => $request->description,
                'price'         => $request->price,
                'stok'         => $request->stok
            ]);
        } else {

            //update product without image
            $product->update([
                'title'         => $request->title,
                'description'   => $request->description,
                'price'         => $request->price,
                'stok'         => $request->stok
            ]);
        }

        //redirect to index
        return redirect()->route('product.index')->with(['success' => 'Data Berhasil Diubah!']);
    }

    /**
     * destroy
     *
     * @param  mixed $id
     * @return RedirectResponse
     */
    public function destroy($id): RedirectResponse
    {
        //get product by ID
        $product = Product::findOrFail($id);

        //delete image
        Storage::delete('public/products/' . $product->image);

        //delete product
        $product->delete();

        //redirect to index
        return redirect()->route('product.index')->with(['success' => 'Data Berhasil Dihapus!']);
    }
    public function addStock(Request $request, Product $product)
    {
        $request->validate([
            'additional_stock' => 'required|integer|min:1'
        ]);

        // Menambah stok produk
        $product->stok += $request->additional_stock;
        $product->save();

        return redirect()->route('product.index')->with('success', 'Stok produk berhasil ditambah.');
    }
}
