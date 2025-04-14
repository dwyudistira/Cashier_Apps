<?php

namespace App\Http\Controllers;

use App\Models\Sales;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function admin(){
        return view("Admin.index");
    }

    public function petugas(){
        $totalPembelian = Sales::whereDate('created_at', today())
            ->sum('quantity');
    
        return view('petugas.index', compact('totalPembelian'));
    }

        public function getChartData()
    {
        $bulanIni = Carbon::now()->month;
        $tahunIni = Carbon::now()->year;
        
        $barData = Sales::whereMonth('created_at', $bulanIni)
            ->whereYear('created_at', $tahunIni)
            ->selectRaw('DAY(created_at) as day, SUM(quantity) as total')
            ->groupBy('day')
            ->pluck('total', 'day');
    
        $pieData = Sales::join('products', 'purchases.product_id', '=', 'products.id')
            ->selectRaw('products.name as product_name, SUM(purchases.quantity) as total')
            ->groupBy('products.name')
            ->get()
            ->map(function ($item) {
                return [
                    'name' => $item->product_name,
                    'value' => (int) $item->total 
                ];
            });
    
        return response()->json([
            'bar' => $barData,
            'pie' => $pieData
        ]);
    }
}
