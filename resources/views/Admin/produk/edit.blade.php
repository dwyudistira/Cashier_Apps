<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-gray-900 leading-tight">
            {{ __('🛒 Update Produk') }}
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto mt-12 p-8 bg-white shadow-lg rounded-2xl border border-gray-100">
        @if(session('success'))
            <div class="mb-4 px-4 py-3 bg-green-100 text-green-700 border border-green-300 rounded-md">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 px-4 py-3 bg-red-100 text-red-700 border border-red-300 rounded-md">
                {{ session('error') }}
            </div>
        @endif

        <div class="mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Edit Produk</h3>
        </div>

        <form action="{{ route('admin.product.update', $products->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama Produk</label>
                <input type="text" name="name" value="{{ $products->name }}" required
                       class="w-full pl-3 pr-5 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-400 ">
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="price" class="block text-sm font-semibold text-gray-700 mb-1">Harga</label>
                <div class="relative">
                    <span class="absolute left-4 top-3 text-gray-600">Rp</span>
                    <input type="text" id="price_display" value="{{ number_format($products->price, 0, ',', '.') }}"
                           class="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200 focus:outline-none">
                    <input type="hidden" name="price" id="price" value="{{ $products->price }}">
                </div>
                @error('price') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="stock" class="block text-sm font-semibold text-gray-700 mb-1">Stok</label>
                <input type="number" name="stock" value="{{ $products->stock }}" required min="1" readonly
                    class="w-full px-3 py-3 border border-gray-300 bg-gray-100 text-gray-700 rounded-md shadow-sm focus:ring-2 focus:ring-indigo-400">
                @error('stock') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label for="image" class="block text-sm font-semibold text-gray-700 mb-1">Gambar Produk</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full pl-3 pr-5 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-400  bg-gray-50">
                @error('image') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <button type="submit"
                        class="w-full bg-indigo-600 text-white py-3 px-6 rounded-md shadow-md hover:bg-indigo-700 transition duration-200 ease-in-out">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('price_display').addEventListener('input', function (e) {
            let value = this.value.replace(/\D/g, ""); // Hapus semua karakter non-angka
            let formatted = new Intl.NumberFormat('id-ID').format(value); // Format angka (Indonesia)
            this.value = formatted; // Update tampilan dengan format
            document.getElementById('price').value = value; // Simpan angka murni di input hidden
        });
    </script>
</x-app-layout>
