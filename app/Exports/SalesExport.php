<?php

namespace App\Exports;

use App\Models\Sales;
use App\Models\Member;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromCollection, WithHeadings, WithMapping
{
    protected $groupedSales;

    public function __construct()
    {
        // Group Sales by invoice_number
        $allSales = Sales::all();
        $this->groupedSales = $allSales->groupBy('invoice_number');
    }

    public function collection()
    {
        // Flatten the grouped collection into just one per invoice
        return $this->groupedSales->map(function ($items) {
            return $items->first(); // Ambil satu sales item per invoice (untuk map-nya)
        })->values(); // supaya clean index
    }

    public function map($sale): array
    {
        // Ambil semua sale berdasarkan invoice_number
        $salesByInvoice = $this->groupedSales[$sale->invoice_number];

        // Ambil member info
        $member = $sale->member_id ? Member::find($sale->member_id) : null;

        // Format produk
        $produkStr = '';
        foreach ($salesByInvoice as $item) {
            $product = json_decode($item->product_data, true);
            if (is_array($product)) {
                $nama = $product['nama'] ?? 'Produk';
                $jumlah = $product['jumlah'] ?? 0;
                $subtotal = $product['subtotal'] ?? 0;
                $produkStr .= "{$nama} ( {$jumlah} : Rp. " . number_format($subtotal, 0, ',', '.') . " ) , ";
            }
        }

        $subtotal = $salesByInvoice->sum('subtotal');
        $totalPaid = $sale->total_paid ?? 0;
        $diskonPoin = $sale->total_discount ?? 0;
        $kembalian = $totalPaid - $subtotal;

        return [
            $member->name ?? 'Bukan Member',
            $member->phone_number ?? '-',
            $member->points ?? '-',
            $produkStr,
            'Rp. ' . number_format($subtotal, 0, ',', '.'),
            'Rp. ' . number_format($totalPaid, 0, ',', '.'),
            'Rp. ' . number_format($diskonPoin, 0, ',', '.'),
            'Rp. ' . number_format($kembalian, 0, ',', '.'),
            optional($sale->created_at)->format('d-m-Y'),
        ];
    }

    public function headings(): array
    {
        return [
            'Nama Pelanggan',
            'No HP Pelanggan',
            'Poin Pelanggan',
            'Produk',
            'Total Harga',
            'Total Bayar',
            'Total Diskon Poin',
            'Total Kembalian',
            'Tanggal Pembelian',
        ];
    }
}
