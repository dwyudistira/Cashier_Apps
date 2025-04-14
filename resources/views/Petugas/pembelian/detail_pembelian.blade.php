<x-app-layout>
    <form id="checkout-form" method="POST">
        @csrf
        <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-4xl mx-auto flex flex-row gap-8">
            <div class="flex-1">
                <h2 class="text-xl font-bold mb-4">Produk yang dipilih</h2>
                <div class="mb-4">
                    @forelse ($cartData as $item)
                        <div class="border-b pb-2 mb-2">
                            <p class="font-semibold">{{ $item['nama'] }}</p>
                            <p>Rp. {{ number_format($item['subtotal'], 0, ',', '.') }} x {{ $item['jumlah'] }}</p>
                        </div>
                    @empty
                        <p class="text-center text-gray-500">Tidak ada produk yang dipilih</p>
                    @endforelse
                </div>

                <div class="flex justify-between font-bold text-lg pt-2">
                    <span>Total</span>
                    <span>
                        Rp. {{ number_format(collect($cartData)->sum('subtotal'), 0, ',', '.') }}
                    </span>
                </div>
            </div>

            <div class="flex-1">
                <div class="mt-6">
                    <label class="block font-bold">Status Pembeli</label>
                    <select id="member-status" name="status" class="w-full p-2 border rounded mt-1">
                        <option value="non-member">Non-Member</option>
                        <option value="member">Member</option>
                    </select>
                </div>
                    
                <div id="member-form" class="mt-4 hidden">
                    <label class="block font-bold">No Telepon (Member)</label>
                    <input type="text" id="no-telepon" name="phone_number" class="w-full p-2 border rounded mt-1">
                </div>

                <div class="mt-4">
                    <label for="price_display" class="block text-sm font-medium text-gray-700">Harga Total</label>
                    <div class="relative">
                        <span class="absolute left-3 top-2 text-gray-700">Rp</span>
                        <input type="text" name="price" id="price"
                            class="mt-1 block w-full pl-10 pr-4 py-2 border border-gray-300 rounded-md shadow-sm focus:ring focus:ring-indigo-200">
                        <input type="hidden" name="price_1">
                    </div>
                    @error('price') 
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p> 
                    @enderror
                </div>

                <div class="flex justify-end mt-4">
                    <button type="submit" id="pesan" class="bg-blue-600 text-white px-6 py-2 rounded hover:bg-blue-700">
                        Pesan
                    </button>
                </div>

            </div>
        </div>
    </form>

    <script>
        document.addEventListener("DOMContentLoaded", function () {
        const memberStatusSelect = document.getElementById("member-status");
        const memberForm = document.getElementById("member-form");
        const form = document.getElementById("checkout-form");
        const submitButton = document.getElementById("pesan");
        
        // Get CSRF token - try multiple ways to be safe
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content || 
                        document.head.querySelector('meta[name="csrf-token"]')?.content ||
                        '{{ csrf_token() }}';

        if (!csrfToken) {
            console.error('CSRF token not found!');
            alert('System error: Security token missing. Please refresh the page.');
            return;
        }

        // Toggle member form visibility
        memberStatusSelect.addEventListener("change", function () {
            memberForm.classList.toggle("hidden", this.value !== "member");
        });

        // Form submission handler
        form.addEventListener("submit", async function(e) {
            e.preventDefault();
            
            // Validate price
            const price = document.getElementById("price").value;
            if (!price || price === "0") {
                alert("Harga harus diisi sebelum melanjutkan!");
                return false;
            }

            // Disable button during submission
            const originalText = submitButton.innerHTML;
            submitButton.disabled = true;
            submitButton.innerHTML = '<span class="animate-spin">⏳</span> Memproses...';

            try {
                const isMember = memberStatusSelect.value === "member";
                const url = isMember 
                    ? "{{ route('petugas.pembelian.memberPage') }}" 
                    : "{{ route('petugas.pembelian.receipt_store') }}";
                
                const formData = new FormData(form);
                
                // For debugging - log form data
                console.log('Submitting form data:', Object.fromEntries(formData));

                const response = await fetch(url, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                });

                // Handle response
                if (!response.ok) {
                    const errorData = await response.json();
                    throw new Error(errorData.message || 'Terjadi kesalahan server');
                }

                const data = await response.json();
                
                if (data.redirect) {
                    window.location.href = data.redirect;
                } else {
                    alert(data.message || 'Transaksi berhasil diproses');
                    window.location.reload();
                }

            } catch (error) {
                console.error('Submission error:', error);
                alert(error.message || 'Terjadi kesalahan saat memproses pembelian');
            } finally {
                submitButton.disabled = false;
                submitButton.innerHTML = originalText;
            }
        });
        document.getElementById('price').addEventListener('input', function (e) {
                let value = this.value.replace(/\D/g, ""); 
                this.value = new Intl.NumberFormat('id-ID').format(value);
                document.getElementById('price').value = value;
        });
    });
    </script>
</x-app-layout>
