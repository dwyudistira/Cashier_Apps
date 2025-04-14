<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Product;
use App\Models\Sales;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\Calculation\MathTrig\Subtotal;

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
                'product_data' => $items->pluck('product_data')->unique()->implode(', '), // Menambahkan product_data
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
        
        return view('petugas.pembelian.index', compact('purchases', 'totalSubtotal'));
    }
    
    public function create()
    {
        $products = Product::all();
        return view("petugas.pembelian.create", compact('products'));
    }

    public function detail(Request $request)
    {
        $cartData = json_decode($request->input('cart_data'), true);
        session(['cart_data' => $cartData]);
        

        return view('petugas.pembelian.detail_pembelian', compact('cartData'));
    }

    // Non-Member

    public function receiptNonMember() { 
        $cartData = session('cart_data');
    
        $sales = Sales::latest()->first();
    
        $members = Member::latest()->first();
    
        $subtotal = array_sum(array_column($cartData, 'subtotal'));
    
        $kembalian = $sales->total_paid - $subtotal;
    
        return view("petugas.pembelian.receipt_non_member", compact('cartData', 'sales', 'members', 'kembalian', 'subtotal'));
    }
    

    public function storeNonMember(Request $request)
    {
        try {
            $cartData = session('cart_data');

            $request->validate([
                'price' => 'required|integer|min:1',
            ]);

            $invoiceNumber = 'INV-' . strtoupper(Str::random(8));

            foreach ($cartData as $item) {
                // Kurangi stok produk
                $product = Product::find($item['id']);
                if ($product) {
                    $product->stock -= $item['jumlah'];
                    $product->save();
                }
            
                Sales::create([
                    'invoice_number' => $invoiceNumber,
                    'name'           => 'Non Member',
                    'product_id'     => $item['id'],
                    'member_id'      => null,
                    'product_data'   => json_encode($item),
                    'quantity'       => $item['jumlah'],
                    'subtotal'       => $item['subtotal'],
                    'total_paid'     => $request->price,
                    'made_by'        => Auth::user()->name,
                ]);
            }
            
            return response()->json([
                'success' => true,
                'redirect' => route('petugas.pembelian.receipt'),
                'message' => 'Pembelian berhasil disimpan untuk Non-Member'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    //Member
    public function memberPage(Request $request)
    {
        $cartData = session('cart_data');

        $sales = Sales::latest()->first();

        $members = Member::latest()->first();

        $points = Member::all();



        return view('petugas.pembelian.detail_member', compact('cartData', 'sales', 'members'));
    }

    public function receiptMember(){
        
        $cartData = session('cart_data');
    
        $sales = Sales::latest()->first();
    
        $members = Member::latest()->first();
    
        $subtotal = array_sum(array_column($cartData, 'subtotal'));
    
        $kembalian = $sales->total_paid - $subtotal;
  
        return view("petugas.pembelian.receipt_member", compact('cartData', 'sales', 'members', 'kembalian', 'subtotal'));
    }

    public function storeMember(Request $request)
    {
        try {
            $cartData = session('cart_data');

            
            $request->validate([
                'phone_number' => 'required|string',
                'price' => 'required|integer|min:1',
                'name' => 'nullable|string|max:255',
                'join_in' => 'nullable|date',
            ]);

            $member = DB::table('members')->where('phone_number', $request->phone_number)->first();

            if (!$member) {
                $totalSubtotal = array_sum(array_column($cartData, 'subtotal'));

                $points = $totalSubtotal / 100;
            
                $memberId = DB::table('members')->insertGetId([
                    'phone_number' => $request->phone_number,
                    'name' => $request->name ?? 'Member Baru',
                    'member_code' => 'MEM-' . strtoupper(Str::random(8)),
                    'join_in' => now(),
                    'points' => $points,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $member = DB::table('members')->find($memberId);
            } else {
                $totalSubtotal = array_sum(array_column($cartData, 'subtotal'));
                $points = $totalSubtotal / 100;
                
                DB::table('members')->where('id', $member->id)->update([
                    'points' => $member->points + $points,  
                    'updated_at' => now(),
                ]);
            
                $member = DB::table('members')->find($member->id);
            }
            
            
            $invoiceNumber = 'INV-' . strtoupper(Str::random(8));
            
            foreach ($cartData as $item) {
                Sales::create([
                    'invoice_number' => $invoiceNumber,
                    'name' => $member->name,
                    'product_id' => $item['id'],
                    'member_id' => $member->id,
                    'product_data' => json_encode($item),
                    'quantity' => $item['jumlah'],
                    'subtotal' => $item['subtotal'],
                    'total_paid' => $request->price, 
                    'made_by' => Auth::user()->name,
                ]);
            }
            
            return response()->json([
                'success' => true,
                'redirect' => route('petugas.pembelian.member'),
                'message' => 'Pembelian berhasil disimpan untuk Member'
            ]);

        } catch (\Exception $e) {   
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function simpanMember(Request $request)
    {
        $request->validate([
            'nama' => 'required|string',
            'poin' => 'required|numeric',
            'total_bayar' => 'required|numeric',
            'member_id' => 'required|exists:members,id',
        ]);
    
        $member = Member::findOrFail($request->member_id);
    
        $gunakanPoin = $request->has('gunakan_poin');
        $poin_digunakan = 0;
        $total_bayar = $request->total_bayar;
        $poin_value = 100; // Misal: 1 poin = Rp 100
        $harga_akhir = $total_bayar; // Harga final yang dibayar
        $diskon_member = 0;
    
        if ($gunakanPoin && $member->points > 0) {
            $potongan = $member->points * $poin_value;
    
            if ($potongan >= $total_bayar) {
                $poin_digunakan = ceil($total_bayar / $poin_value);
                $harga_akhir = 0;
            } else {
                $poin_digunakan = $member->points;
                $harga_akhir = $total_bayar - $potongan;
            }
    
            $diskon_member = $poin_digunakan * $poin_value;
    
            // Update poin member
            $member->points -= $poin_digunakan;
            $member->save();
        }
    
        // Simpan transaksi ke Sales
        Sales::create([
            'invoice_number' => 'INV-' . strtoupper(uniqid()),
            'name' => $member->name,
            'product_id' => null,
            'member_id' => $member->id,
            'product_data' => null,
            'quantity' => 1,
            'subtotal' => $total_bayar,
            'diskon_point' => $diskon_member,
            'points' => $poin_digunakan,
            'total_paid' => $harga_akhir,
        ]);
    
        return redirect()->route('petugas.pembelian.receipt_member')->with('success', 'Transaksi berhasil disimpan.');
    }
    
    // Export
    public function exportExcel()
    {
        // Coming soon
    }

    public function exportPdf()
    {
        // Coming soon
    }
}
