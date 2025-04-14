<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    //

    public function index(){
        $products = Product::paginate(10);

        return view("admin.produk.index", compact('products'));
    }

    public function create(){
        return view("admin.produk.create");
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric',  
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('product', 'public'); 
        }
    
        Product::create($validated);
    
        return redirect()->route('admin.product')->with('success', 'Produk berhasil ditambahkan');
    }

    public function edit($id){
        $products = Product::findOrFail($id);

        return view('admin.produk.edit', compact('products'));
    }

    public function update(Request $request, $id){
        $data = Product::find($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'stock' => 'required|integer',
            'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $data->update($validated);

        return redirect()->route('admin.product');
    }
    public function updateStock(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:products,id',
            'stock' => 'required|integer|min:0',
        ]);
    
        $product = Product::findOrFail($request->id);
        $product->stock = $request->stock;
        $product->save();
    
        return redirect()->route('admin.product')->with('success', 'Stok produk berhasil diperbarui!');
    }    

    public function destroy($id){
        $data = Product::find($id);

        $data->delete();

        return redirect('product');
    }

}
