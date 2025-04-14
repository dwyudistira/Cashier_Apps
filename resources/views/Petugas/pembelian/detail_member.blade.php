<x-app-layout>
    <div class="bg-white p-10 rounded-2xl shadow-xl w-full max-w-6xl mx-auto flex flex-col md:flex-row gap-10 border border-gray-200">
        <!-- Bagian Produk -->
        <div class="flex-1 border border-gray-200 p-6 rounded-xl bg-gray-50">
            <h2 class="text-2xl font-bold text-gray-700 mb-4">Ringkasan Produk</h2>
            <div class="overflow-x-auto">
                <table class="w-full text-left text-sm">
                    <thead class="border-b">
                        <tr class="text-gray-600">
                            <th class="pb-3">Nama Produk</th>
                            <th class="pb-3">Qty</th>
                            <th class="pb-3">Harga</th>
                            <th class="pb-3">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="text-gray-800 font-medium">
                        @foreach($cartData as $data)
                            <tr>
                                <td class="py-2">{{ $data['nama'] }}</td>
                                <td>{{ $data['jumlah'] }}</td>
                                <td>{{ number_format($data['subtotal'], 0, ',', '.') }}</td>
                                <td>{{ number_format($data['subtotal'], 0, ',', '.') }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>

            <div class="mt-6 space-y-2 text-right">
                <div class="font-semibold text-lg text-gray-700">
                    <p>Total Harga</p>
                    <p id="total-harga" class="text-xl text-blue-700 font-bold">Rp. {{ number_format($data['subtotal'], 0, ',', '.') }}</p>
                </div>
                <div class="font-semibold text-lg text-gray-700">
                    <p>Total Bayar</p>
                    <p id="total-bayar" class="text-xl text-green-600 font-bold">Rp. {{ number_format($sales->total_paid, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        <!-- Bagian Form -->
        <div class="flex-1 border border-gray-200 p-6 rounded-xl">
            <h2 class="text-2xl font-bold text-gray-700 mb-4">Detail Member</h2>

            <form action="{{ route('petugas.pembelian.simpan_member') }}" method="POST">
                @csrf

                {{-- Nama Member --}}
                <div class="mb-5">
                    <label class="block font-semibold text-gray-600">Nama Member</label>
                    <input type="text" name="nama" class="w-full p-3 border border-gray-300 rounded-md mt-1 focus:ring focus:ring-blue-100" value="{{ $sales->name }}" readonly>
                </div>

                {{-- Poin --}}
                <div class="mb-5">
                    <label class="block font-semibold text-gray-600">Poin</label>
                    <input type="number" name="poin" class="w-full p-3 bg-gray-100 border border-gray-300 rounded-md mt-1" value="{{ $members->points }}" readonly>
                </div>

                {{-- Gunakan Poin --}}
                <div class="flex items-center gap-3 mb-6">
                    <input type="checkbox" id="gunakan-poin" name="gunakan_poin" value="1" class="form-check-input rounded">
                    <label for="gunakan-poin" class="text-gray-700 font-medium">Gunakan Poin</label>
                </div>

                {{-- ID Member atau Nomor Telepon --}}
                <input type="hidden" name="member_id" value="{{ $members->id }}">
                <input type="hidden" name="total_bayar" value="{{ $sales->total_paid }}">

                {{-- Submit --}}
                <div class="flex justify-end">
                    <button type="submit" class="bg-blue-600 text-white px-8 py-3 rounded-lg shadow-md hover:bg-blue-700 transition duration-200">Selanjutnya</button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
