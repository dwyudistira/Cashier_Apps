<x-app-layout>
    @if(session('success'))
        <div class="alert alert-success text-center">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger text-center">
            {{ session('error') }}
        </div>
    @endif

    <div class="container mt-4">
        <div class="row justify-content-center">
            @foreach ($products as $product)
                <div class="col-md-4 mb-4">
                    <div class="card shadow-sm border-0 p-3 text-center">
                        @if ($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" 
                                 class="card-img-top mx-auto d-block" 
                                 style="width: 250px; height: 150px; object-fit: cover; border-radius: 10px;" 
                                 alt="{{ $product->name }}">
                        @else
                            <div class="d-flex align-items-center justify-content-center bg-light"
                                 style="width: 150px; height: 150px; border-radius: 10px;">
                                <span>No Image</span>
                            </div>
                        @endif
                        <div class="card-body">
                            <h5 class="card-title">{{ $product->name }}</h5>
                            <p class="text-muted">Stok: <span id="stok-{{ $product->id }}">{{ $product->stock }}</span></p>
                            <p class="fw-bold text-primary">Rp. {{ number_format($product->price, 0, ',', '.') }}</p>

                            <div class="d-flex align-items-center justify-content-center">
                                <button class="btn btn-sm me-2" onclick="kurangi({{ $product->id }}, {{ $product->price }}, '{{ $product->name }}')" type="button">-</button>
                                <span id="jumlah-{{ $product->id }}" class="fw-bold">0</span>
                                <button class="btn btn-sm ms-2" onclick="tambah({{ $product->id }}, {{ $product->price }}, '{{ $product->name }}')" type="button">+</button>
                            </div>

                            <p class="mt-2">Sub Total: <strong>Rp. <span id="subtotal-{{ $product->id }}">0</span></strong></p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <div class="text-center mt-4">
        <form id="cartForm" method="POST" action="{{ route('petugas.pembelian.detail') }}">
            @csrf
            <input type="hidden" name="cart_data" id="cartData">
            <button type="submit" onclick="kirimDataKeHalamanBerikutnya()" class="btn btn-primary">Selanjutnya</button>
        </form>
    </div>

    <script>
        let cart = {}; 

        function tambah(id, harga, nama) {
            let jumlahSpan = document.getElementById("jumlah-" + id);
            let subtotalSpan = document.getElementById("subtotal-" + id);
            let jumlah = parseInt(jumlahSpan.textContent) + 1;

            jumlahSpan.textContent = jumlah;
            subtotalSpan.textContent = (jumlah * harga).toLocaleString("id-ID");
            
            cart[id] = { id, nama, jumlah, subtotal: jumlah * harga };
            localStorage.setItem("cart", JSON.stringify(cart));
        }

        function kurangi(id, harga, nama) {
            let jumlahSpan = document.getElementById("jumlah-" + id);
            let subtotalSpan = document.getElementById("subtotal-" + id);
            let jumlah = Math.max(0, parseInt(jumlahSpan.textContent) - 1);

            jumlahSpan.textContent = jumlah;
            subtotalSpan.textContent = (jumlah * harga).toLocaleString("id-ID");

            if (jumlah === 0) {
                delete cart[id];
            } else {
                cart[id] = { id, nama, jumlah, subtotal: jumlah * harga };
            }
            localStorage.setItem("cart", JSON.stringify(cart));
        }

        function kirimDataKeHalamanBerikutnya() {
            document.getElementById("cartData").value = JSON.stringify(cart);
        }
    </script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</x-app-layout>
