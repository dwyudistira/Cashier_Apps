<x-app-layout>

    <div class="max-w-5xl mx-auto mt-12 p-10 bg-white shadow-2xl rounded-3xl border border-gray-100">
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

        <div class="mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Tambah User</h3>
        </div>

        <form action="{{ route('admin.user.store') }}" method="POST" class="space-y-8">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                <div>
                    <label for="name" class="block text-sm font-bold text-gray-700 mb-2">Nama</label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        class="w-full px-5 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                    @error('name') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-gray-700 mb-2">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        class="w-full px-5 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                    @error('email') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-bold text-gray-700 mb-2">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-5 py-3 border border-gray-300 rounded-xl shadow-sm focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                    @error('password') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                </div>

                <div>
                    <label for="role" class="block text-sm font-bold text-gray-700 mb-2">Role</label>
                    <select name="role" id="role" required
                        class="w-full px-5 py-3 border border-gray-300 rounded-xl shadow-sm bg-white focus:ring-2 focus:ring-indigo-400 focus:outline-none">
                        <option value="">-- Pilih Role --</option>
                        <option value="Administrator" {{ old('role') == 'Administrator' ? 'selected' : '' }}>Admin</option>
                        <option value="Petugas" {{ old('role') == 'Petugas' ? 'selected' : '' }}>Petugas</option>
                    </select>
                    @error('role') <p class="text-red-500 text-sm mt-2">{{ $message }}</p> @enderror
                </div>
            </div>

            <div class="pt-6">
                <button type="submit"
                    class="w-full bg-indigo-600 text-white text-lg font-semibold py-3 px-6 rounded-xl hover:bg-indigo-700 transition duration-300 ease-in-out shadow-md">
                    Simpan User
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
