<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-gray-800 leading-tight">
            {{ __('Tambah User') }}
        </h2>
    </x-slot>

    <div class="max-w-5xl mx-auto mt-12 p-8 bg-white shadow-lg rounded-2xl">
        @if(session('success'))
            <div class="mb-4 text-green-600 font-semibold">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 text-red-600 font-semibold">
                {{ session('error') }}
            </div>
        @endif
        <div class="mb-8">
            <h3 class="text-2xl font-bold text-gray-800 mb-2">Edit User</h3>
        </div>

        <form action="{{ route('admin.user.update', $users->id) }}" method="POST" class="space-y-6">
            @csrf
            @method('PUT')

            {{-- Nama --}}
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-1">Nama</label>
                <input type="text" name="name" value="{{ $users->name }}" required
                    class="w-full px-4 py-3 border border-gray-300 bg-gray-50 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition">
                @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Email --}}
            <div>
                <label for="email" class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ $users->email }}" required
                    class="w-full px-4 py-3 border border-gray-300 bg-gray-50 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition">
                @error('email') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Password --}}
            <div>
                <label for="password" class="block text-sm font-semibold text-gray-700 mb-1">Password</label>
                <input type="password" name="password" value="{{ old('password') }}" required
                    class="w-full px-4 py-3 border border-gray-300 bg-gray-50 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition">
                @error('password') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Role --}}
            <div>
                <label for="role" class="block text-sm font-semibold text-gray-700 mb-1">Role</label>
                <select name="role" id="role" required
                    class="w-full px-4 py-3 border border-gray-300 bg-gray-50 rounded-xl shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-400 focus:border-indigo-400 transition">
                    <option value="Administrator" {{ old('role', $users->role ?? '') == 'Administrator' ? 'selected' : '' }}>Admin</option>
                    <option value="Petugas" {{ old('role', $users->role ?? '') == 'Petugas' ? 'selected' : '' }}>Petugas</option>
                </select>
                @error('role') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Tombol --}}
            <div>
                <button type="submit"
                    class="w-full bg-indigo-600 text-white py-3 px-6 rounded-xl font-semibold hover:bg-indigo-700 transition shadow-md">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</x-app-layout>
