<x-app-layout>
    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-lg border border-gray-100">
        <!-- Header dengan tombol aksi yang lebih stylish -->
        <div class="flex justify-between mb-8 items-center">
            <div>
                <h1 class="text-3xl font-bold text-gray-800">INVOICE</h1>
                <div class="flex items-center mt-2">
                    <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-3 py-1 rounded-full">#{{ $sales->invoice_number }}</span>
                    <span class="text-gray-500 ml-3">{{ now()->format('d F Y') }}</span>
                </div>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('petugas.pembelian.export-pdf') }}" class="flex items-center bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition-all duration-200 shadow-md hover:shadow-lg">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Unduh
                </a>
            </div>
        </div>

        <!-- Garis pemisah dekoratif -->
        <div class="border-t-2 border-b-2 border-gray-200 py-1 my-6"></div>

        <!-- Tabel produk yang lebih elegan -->
        <div class="mb-8 overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b-2 border-gray-200">
                        <th class="text-left pb-3 text-lg font-semibold text-gray-700">Produk</th>
                        <th class="text-right pb-3 text-lg font-semibold text-gray-700">Harga</th>
                        <th class="text-right pb-3 text-lg font-semibold text-gray-700">Quantity</th>
                        <th class="text-right pb-3 text-lg font-semibold text-gray-700">Sub Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cartData as $item)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                            <td class="py-4 text-gray-800 font-medium">{{ $item['nama'] }}</td>
                            <td class="text-right text-gray-600">Rp. {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                            <td class="text-right text-gray-600">{{ $item['jumlah'] }}</td>
                            <td class="text-right text-blue-600 font-semibold">Rp. {{ number_format($item['subtotal'], 0, ',', '.') }}</td>
                        @endforeach
                    </tr>
                    <!-- Baris tambahan produk bisa ditambahkan di sini -->
                </tbody>
            </table>
        </div>

        <!-- Informasi pembayaran dalam card -->
        <div class="grid grid-cols-2 gap-6 mb-8">
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-100">
                <p class="text-sm font-medium text-blue-800 mb-1">POIN DIGUNAKAN</p>
                <p class="text-2xl font-bold text-blue-600">{{ number_format($points, 0, ',', '.')}}</p>
            </div>
            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200 text-right">
                <p class="text-sm font-medium text-gray-700 mb-1">KASIR</p>
                <p class="text-xl font-semibold text-gray-800">{{ Auth::user()->name }}</p>
            </div>
        </div>

        <!-- Total pembayaran yang menonjol -->
        <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
            <div class="flex justify-between items-center mb-3">
                <span class="text-lg font-medium text-gray-700">REMBALLAN</span>
                <span class="text-lg font-medium text-gray-800">Rp. {{ $kembalian }}</span>
            </div>
            <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                <span class="text-2xl font-bold text-gray-800">TOTAL</span>
                <span class="text-2xl font-bold text-blue-600">Rp. {{ number_format($subtotal, 0, ',', '.') }}</span>
            </div>
        </div>

        <!-- Footer invoice -->
        <div class="mt-8 text-center text-sm text-gray-500">
            <p>Terima kasih telah berbelanja bersama kami</p>
            <p class="mt-1">Invoice ini sah dan diproses oleh sistem</p>
        </div>
    </div>

    <style>
        /* Animasi halus untuk hover */
        button {
            transition: all 0.2s ease-in-out;
        }
        
        /* Efek saat tombol diklik */
        button:active {
            transform: scale(0.98);
        }
        
        /* Tampilan untuk print */
        @media print {
            button {
                display: none !important;
            }
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
                padding: 20px !important;
            }
        }
    </style>
</x-app-layout>