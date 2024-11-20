<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $data = Product::latest()->paginate(10);
        $best = product::where('qty_out', '>=', 5)->get();
        $countKeranjang = Auth::check() ? Auth::user()->carts->count() : 0;
        
        return view('user.index', [
            'title' => 'home',
            'data' => $data,
            'best' => $best,
            'count'=> $countKeranjang
        ]);
    }
}
