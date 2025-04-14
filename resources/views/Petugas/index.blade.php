<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <!-- Sales Summary -->
            <div class="bg-white shadow-md rounded-lg p-6 text-gray-900 w-full md:w-[980px] mx-auto">
                <h2 class="text-xl font-semibold text-gray-900 text-center">Selamat Datang, Petugas!</h2>
                
                <div class="mt-6 bg-gray-100 rounded-xl p-6 text-center">
                    <h3 class="text-gray-500 text-sm font-medium">Total Penjualan Hari Ini ({{ now()->format('d M Y') }})</h3>
                    
                    <p class="text-5xl font-bold text-gray-900 mt-2">
                        {{ $totalPembelian }}
                    </p>
                    
                    <p class="text-gray-500 mt-1">
                        Jumlah total penjualan yang terjadi hari ini.
                    </p>
                </div>

                <p class="text-gray-400 text-sm text-center mt-4">
                    Terakhir diperbarui: {{ now()->format('d M Y H:i') }}
                </p>
            </div>
        </div>
    </div>
</x-app-layout>
