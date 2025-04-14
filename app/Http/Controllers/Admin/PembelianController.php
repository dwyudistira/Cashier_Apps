<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PembeliansExport;
use App\Exports\SalesExport;
use App\Http\Controllers\Controller;
use App\Models\Pembelian;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Maatwebsite\Excel\Facades\Excel;

class PembelianController extends Controller
{
    
    public function index()
    {
        // Ambil semua data dari Sales
        $allSales = Sales::all();
    
        // Group berdasarkan invoice_number
        $grouped = $allSales->groupBy('invoice_number');
    
        // Mapping hasil group jadi ringkasan per invoice
        $collection = $grouped->map(function ($items, $invoiceNumber) {
            return [
                'invoice_number' => $invoiceNumber,
                'subtotal' => $items->sum('subtotal'),
                'name' => $items->first()->name,
                'created_at' => $items->first()->created_at,
                'made_by' => $items->first()->made_by,
            ];
        })->values(); // Jadi Collection dengan index numerik
    
        // Hitung total subtotal
        $totalSubtotal = $collection->sum('subtotal');
    
        // Paginate manual
        $perPage = 10;
        $currentPage = request()->get('page', 1);
        $currentItems = $collection->slice(($currentPage - 1) * $perPage, $perPage)->values();
        $purchases = new LengthAwarePaginator($currentItems, $collection->count(), $perPage, $currentPage, [
            'path' => request()->url(),
            'query' => request()->query(),
        ]);
    
        return view('admin.pembelian.index', compact('purchases', 'totalSubtotal'));
    }
    

    public function create()
    {
        return view("admin.pembelian.create", compact('purchases'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'phone_number' => 'required|numeric|digits_between:11,18',
            'points'       => 'required|integer|min:1',
            'quantity'     => 'required|integer|min:1',
            'product_id'   => 'nullable|integer|exists:product,id',
        ]);
        
        $data = Sales::create($validated);

        return redirect()->route('admin.pembelian')->with('success', 'Pembelian berhasil disimpan!');
    }

    public function edit($id){
        $purchases = Sales::find($id);

        return view('admin.pembelian.edit', compact('purchases'));
    }

    public function update(Request $request, $id){
        $purchases = Sales::find($id);

        $validate = $request->validate([
            'name' => 'required|string|max:255',
            'phone_number' => 'required|numeric|min:7',
            'product_id' => 'required|integer',
            'points' => 'required|integer',
            'quantity' => 'required|integer',
            'made_by' => 'required|string',
        ]);

        $purchases->update($validate);

        return redirect()->route('admin.pembelian');
    }

    public function destroy($id){
        $purchases = Sales::find($id);

        $purchases->delete();

        return redirect()->route('admin.pembelian');
    }

    public function export()
    {
        return Excel::download(new SalesExport, 'pembelian.xlsx');
    }
}