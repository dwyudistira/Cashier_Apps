<?php

namespace App\Http\Controllers\Petugas;

use App\Http\Controllers\Controller;
use App\Models\Member;
use App\Models\Product;
use App\Models\Sales;
use Barryvdh\DomPDF\Facade\Pdf;
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

        $members = Member::find($sales->member_id);

        $points = Member::all();

        return view('petugas.pembelian.detail_member', compact('cartData', 'sales', 'members'));
    }

    public function receiptMember(){
        
        $cartData = session('cart_data');
    
        $sales = Sales::latest()->first();
    
        $members = Member::latest()->first();
        
        $subtotal = array_sum(array_column($cartData, 'subtotal'));

        $points = Member::where('id', $sales->member_id)->value('points');
    
        $kembalian = $sales->total_paid - $points;
  
        return view("petugas.pembelian.receipt_member", compact('cartData', 'sales', 'members', 'kembalian', 'subtotal', 'points'));
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
            'nama' => 'required|string', // Validasi nama
            'poin' => 'required|numeric',
            'total_bayar' => 'required|numeric',
        ]);
    
        // Cari member berdasarkan nama
        $member = Member::where('name', $request->nama)->firstOrFail();
    
        $gunakanPoin = $request->has('gunakan_poin');
        $poin_value = 100;
        $poin_digunakan = 0;
        $harga_akhir = $request->total_bayar;
    
        if ($gunakanPoin && $member->points > 0) {
            $potongan = $member->points * $poin_value;
    
            if ($potongan >= $harga_akhir) {
                $poin_digunakan = ceil($harga_akhir / $poin_value);
                $harga_akhir = 0;
            } else {
                $poin_digunakan = $member->points;
                $harga_akhir -= $potongan;
            }
    
            // Update points member
            $member->points -= $poin_digunakan;
            $member->save();
        }
    
        // Cari sales berdasarkan nama member
        $sale = Sales::where('name', $member->name)->first();
    
        if ($sale) {
            // Kalau ada transaksi sebelumnya, update total_paid
            $sale->total_paid = $harga_akhir;
            $sale->save();
        } else {
            // Kalau belum ada transaksi, buat transaksi baru
            $sale = new Sales();
            $sale->member_id = $member->id;
            $sale->name = $member->name;
            $sale->invoice_number = 'INV-' . strtoupper(uniqid());
            $sale->total_paid = $harga_akhir;
            $sale->save();
        }
    
        return redirect()->route('petugas.pembelian.receipt_member')
                         ->with('success', 'Transaksi berhasil disimpan.');
    }
    
    
    // Export
    public function exportPdf()
    {
        $latestInvoice = Sales::latest('created_at')->first();
    
        if (!$latestInvoice) {
            return redirect()->back()->with('error', 'Tidak ada data transaksi untuk diexport.');
        }
    
        // Ambil semua data berdasarkan invoice_number
        $sales = Sales::where('invoice_number', $latestInvoice->invoice_number)->get();
    
        // Ambil member
        $member = null;
        if ($latestInvoice->member_id) {
            $member = Member::find($latestInvoice->member_id);
        }
    
        // Ambil semua produk dari product_data
        $cartData = $sales->map(function ($sale) {
            $product = json_decode($sale->product_data, true);
            return [
                'nama' => $product['nama'] ?? '-',
                'jumlah' => $product['jumlah'] ?? 0,
                'subtotal' => $product['subtotal'] ?? 0,
            ];
        });
    
        $subtotal = $sales->sum('subtotal');
        $totalPaid = $latestInvoice->total_paid;
        $kembalian = $totalPaid - $subtotal;
        $points = $latestInvoice->points ?? 0;
    
        $data = [
            'sales' => $latestInvoice,
            'cartData' => $cartData,
            'subtotal' => $subtotal,
            'totalPaid' => $totalPaid,
            'kembalian' => $kembalian,
            'user' => Auth::user(),
            'member' => $member,
            'points' => $points
        ];
    
        $pdf = Pdf::loadView('petugas.pembelian.export_pdf', $data)
                 ->setPaper('a4', 'portrait')
                 ->setOption('isRemoteEnabled', true)
                 ->setOption('defaultFont', 'sans-serif');
    
        return $pdf->download('invoice_' . $latestInvoice->invoice_number . '.pdf');
    }
    
    public function exportPdfId($id)
    {
        // Cari data sales berdasarkan ID atau invoice_number
        $sales = Sales::where('invoice_number', $id)->get();
    
        if ($sales->isEmpty()) {
            return redirect()->back()->with('error', 'Data transaksi tidak ditemukan.');
        }
    
        $latestInvoice = $sales->first(); // ambil salah satu record (semua invoice_number-nya sama)
        
        // Ambil member (jika ada)
        $member = null;
        if ($latestInvoice->member_id) {
            $member = Member::find($latestInvoice->member_id);
        }
    
        // Ambil semua produk dari product_data
        $cartData = $sales->map(function ($sale) {
            $product = json_decode($sale->product_data, true);
            return [
                'nama' => $product['nama'] ?? '-',
                'jumlah' => $product['jumlah'] ?? 0,
                'subtotal' => $product['subtotal'] ?? 0,
            ];
        });
    
        $subtotal = $sales->sum('subtotal');
        $totalPaid = $latestInvoice->total_paid;
        $kembalian = $totalPaid - $subtotal;
        $points = $latestInvoice->points ?? 0;
    
        $data = [
            'sales' => $latestInvoice,
            'cartData' => $cartData,
            'subtotal' => $subtotal,
            'totalPaid' => $totalPaid,
            'kembalian' => $kembalian,
            'user' => Auth::user(),
            'member' => $member,
            'points' => $points
        ];
    
        $pdf = Pdf::loadView('petugas.pembelian.export_pdf', $data)
                 ->setPaper('a4', 'portrait')
                 ->setOption('isRemoteEnabled', true)
                 ->setOption('defaultFont', 'sans-serif');
    
        return $pdf->download('invoice_' . $latestInvoice->invoice_number . '.pdf');
    }
    
}
