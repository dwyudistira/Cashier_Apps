<x-app-layout>

    <div class="max-w-6xl mx-auto mt-12 p-10 bg-white shadow-2xl rounded-3xl border border-gray-100">
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 text-green-800 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-6 p-4 bg-red-100 text-red-800 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Penjelasan di atas form -->
    <div class="mb-8">
        <h3 class="text-2xl font-bold text-gray-800 mb-2">Tambah Produk</h3>
    </div>


        <form action="{{ route('admin.product.store') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <!-- Nama Produk -->
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nama Produk</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full pl-3 pr-5 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-400 ">
                    @error('name') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Harga Produk -->
                <div>
                    <label for="price_display" class="block text-sm font-bold text-gray-700 mb-2">Harga</label>
                    <div class="relative">
                        <span class="absolute left-4 top-3 text-gray-500">Rp</span>
                        <input type="text" id="price_display" value="{{ old('price') }}"
                            class="w-full px-5 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-400">
                        <input type="hidden" name="price" id="price" value="{{ old('price') }}">
                    </div>
                    @error('price') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Stok Produk -->
                <div>
                    <label for="stock" class="block text-sm font-bold text-gray-700 mb-2">Stok</label>
                    <input type="number" name="stock" value="{{ old('stock') }}" required min="1"
                        class="w-full pl-3 pr-5 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                    @error('stock') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <!-- Gambar Produk -->
                <div>
                    <label for="image" class="block text-sm font-bold text-gray-700 mb-2">Gambar Produk</label>
                    <input type="file" name="image" accept="image/*"
                        class="w-full pl-3 pr-5 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none bg-gray-50">
                    @error('image') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <!-- Tombol Submit -->
            <div class="pt-4">
                <button type="submit"
                    class="w-full bg-indigo-600 text-white text-lg font-semibold py-3 px-6 rounded-xl hover:bg-indigo-700 transition duration-300 ease-in-out shadow-md">
                    Simpan Produk
                </button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById('price_display').addEventListener('input', function (e) {
            let value = this.value.replace(/\D/g, "");
            let formatted = new Intl.NumberFormat('id-ID').format(value);

            this.value = formatted;
            document.getElementById('price').value = value;
        });
    </script>
</x-app-layout>
