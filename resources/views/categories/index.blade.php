@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold text-center mb-6">Daftar Kategori</h1>

    <!-- Tombol Tambah Kategori -->
    <button onclick="openCreateCategoryModal()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300 mb-4">Tambah Kategori</button>

    <!-- Tabel Kategori -->
    <div class="overflow-x-auto bg-white rounded-lg shadow-md">
        <table class="min-w-full">
            <thead class="bg-gray-200">
                <tr>
                    <th class="px-4 py-2 text-left">Nama Kategori</th>
                    <th class="px-4 py-2 text-left">Jenis</th>
                    <th class="px-4 py-2 text-left">Jumlah Transaksi</th>
                    <th class="px-4 py-2 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($categories as $category)
                    <tr class="border-b">
                        <td class="px-4 py-2">{{ $category->name }}</td>
                        <td class="px-4 py-2">{{ ucfirst($category->type) }}</td>
                        <td class="px-4 py-2">{{ $category->transactions_count }}</td>
                        <td class="px-4 py-2">
                            <!-- Tombol Edit -->
                            <button onclick="openEditCategoryModal({{ $category->id }}, '{{ $category->name }}', '{{ $category->type }}')" class="text-yellow-500 hover:text-yellow-600 transition duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </button>
                            <!-- Tombol Delete -->
                            <button onclick="confirmDeleteCategory({{ $category->id }})" class="text-red-500 hover:text-red-600 transition duration-300 ml-2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<!-- Modal untuk Create Kategori -->
<div id="createCategoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Tambah Kategori Baru</h2>
        <form action="{{ route('categories.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700">Nama Kategori:</label>
                <input type="text" name="name" id="name" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700">Jenis Kategori:</label>
                <select name="type" id="type" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeCreateCategoryModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-300 mr-2">Batal</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal untuk Edit Kategori -->
<div id="editCategoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Edit Kategori</h2>
        <form id="editCategoryForm" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-4">
                <label for="editName" class="block text-sm font-medium text-gray-700">Nama Kategori:</label>
                <input type="text" name="name" id="editName" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="editType" class="block text-sm font-medium text-gray-700">Jenis Kategori:</label>
                <select name="type" id="editType" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeEditCategoryModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-300 mr-2">Batal</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Delete Kategori -->
<div id="deleteCategoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Konfirmasi Hapus Kategori</h2>
        <p class="mb-4">Apakah Anda yakin ingin menghapus kategori ini?</p>
        <div class="flex justify-end">
            <button onclick="closeDeleteCategoryModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-300 mr-2">Batal</button>
            <form id="deleteCategoryForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-300">Hapus</button>
            </form>
        </div>
    </div>
</div>

<!-- Script untuk Mengontrol Modal -->
<script>
    // Fungsi untuk membuka modal create kategori
    function openCreateCategoryModal() {
        document.getElementById('createCategoryModal').classList.remove('hidden');
    }

    // Fungsi untuk menutup modal create kategori
    function closeCreateCategoryModal() {
        document.getElementById('createCategoryModal').classList.add('hidden');
    }

    // Fungsi untuk membuka modal edit kategori
    function openEditCategoryModal(id, name, type) {
        // Isi form dengan data kategori
        document.getElementById('editName').value = name;
        document.getElementById('editType').value = type;

        // Set action form untuk update
        document.getElementById('editCategoryForm').action = `/categories/${id}`;

        // Tampilkan modal
        document.getElementById('editCategoryModal').classList.remove('hidden');
    }

    // Fungsi untuk menutup modal edit kategori
    function closeEditCategoryModal() {
        document.getElementById('editCategoryModal').classList.add('hidden');
    }

    // Fungsi untuk membuka modal konfirmasi delete kategori
    function confirmDeleteCategory(id) {
        // Set action form untuk delete
        document.getElementById('deleteCategoryForm').action = `/categories/${id}`;

        // Tampilkan modal
        document.getElementById('deleteCategoryModal').classList.remove('hidden');
    }

    // Fungsi untuk menutup modal konfirmasi delete kategori
    function closeDeleteCategoryModal() {
        document.getElementById('deleteCategoryModal').classList.add('hidden');
    }
</script>
@endsection