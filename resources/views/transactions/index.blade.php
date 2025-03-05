@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold text-center mb-6">Finance Tracker</h1>
    <!-- Form Filter -->
    <form action="{{ route('transactions.index') }}" method="GET" class="mb-6 bg-white p-4 rounded-lg shadow-md">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Filter Jenis Transaksi -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Jenis Transaksi</label>
                <select name="type" id="type" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Semua</option>
                    <option value="income" {{ $type == 'income' ? 'selected' : '' }}>Income</option>
                    <option value="expense" {{ $type == 'expense' ? 'selected' : '' }}>Expense</option>
                </select>
            </div>

            <!-- Filter Tanggal Awal -->
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Dari Tanggal</label>
                <input type="date" name="start_date" id="start_date" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ $startDate }}">
            </div>

            <!-- Filter Tanggal Akhir -->
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">Sampai Tanggal</label>
                <input type="date" name="end_date" id="end_date" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" value="{{ $endDate }}">
            </div>
        </div>
        <div class="mt-4">
            <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">Filter</button>
            <a href="{{ route('transactions.index') }}" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-300 ml-2">Reset</a>
        </div>
    </form>
    
    <!-- Current Balance -->
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <p class="text-lg font-semibold">Current Balance: <span class="text-blue-600">Rp.{{ number_format($balance, 2) }}</span></p>
    </div>

    <!-- Tombol Add New Transaction -->
    <button onclick="openCreateModal()" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">Add New Transaction</button>
    <button type="button" onclick="openCategoryModal()" class="ml-2 bg-green-500 text-white px-3 py-2 rounded-lg hover:bg-green-600 transition duration-300">Tambah Kategori</button>

    <!-- Div Transaksi -->
    <div class="mt-6 overflow-x-auto">
        <!-- Body -->
        <div class="bg-white rounded-b-lg shadow-md">
            @foreach ($transactions as $transaction)
                <div class="grid grid-cols-3 gap-4 p-4 border-b">
                    <!-- Kolom Deskripsi dan Tipe -->
                    <div>
                        <div>{{ $transaction->category->name ?? '-' }}</div>
                        <div>
                            <span class="{{ $transaction->type == 'income' ? 'text-green-600' : 'text-red-600' }}">
                                {{ ucfirst($transaction->type) }}
                            </span>
                        </div>
                    </div>

                    <!-- Kolom Jumlah dan Tanggal -->
                    <div>
                        <div class="font-medium">Rp.{{ number_format($transaction->amount, 2) }}</div>
                        <div>{{ date('d/m/Y', strtotime($transaction->date)) }}</div>
                    </div>

                    <!-- Kolom Actions -->
                    <div class="flex items-center justify-end">
                        <!-- Tampilan Desktop -->
                        <div class="hidden md:flex space-x-2">
                            <!-- Tombol Edit -->
                            <button onclick="openEditModal({{ $transaction->id }}, '{{ $transaction->date }}', '{{$transaction->category_id}}' , '{{ $transaction->description }}', '{{ $transaction->type }}', {{ $transaction->amount }})" class="text-yellow-500 hover:text-yellow-600 transition duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16.862 4.487l1.687-1.688a1.875 1.875 0 112.652 2.652L10.582 16.07a4.5 4.5 0 01-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 011.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0115.75 21H5.25A2.25 2.25 0 013 18.75V8.25A2.25 2.25 0 015.25 6H10" />
                                </svg>
                            </button>
                            <!-- Tombol Delete -->
                            <button onclick="confirmDelete({{ $transaction->id }})" class="text-red-500 hover:text-red-600 transition duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                </svg>
                            </button>
                        </div>

                        <!-- Tampilan Mobile -->
                        <div class="md:hidden relative">
                            <!-- Tombol Dropdown -->
                            <button onclick="toggleDropdown('dropdown-{{ $transaction->id }}')" class="dropdown-toggle text-gray-500 hover:text-gray-700 transition duration-300">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 12.75a.75.75 0 110-1.5.75.75 0 010 1.5zM12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                                </svg>
                            </button>
                            <!-- Dropdown Menu -->
                            <div id="dropdown-{{ $transaction->id }}" class="dropdown-menu hidden absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 z-10">
                                <button onclick="openEditModal({{ $transaction->id }}, '{{ $transaction->date }}', '{{$transaction->category_id}}' , '{{ $transaction->description }}', '{{ $transaction->type }}', {{ $transaction->amount }})" class="block w-full text-left px-4 py-2 text-yellow-500 hover:bg-gray-100">Edit</button>
                                <button onclick="confirmDelete({{ $transaction->id }})" class="block w-full text-left px-4 py-2 text-red-500 hover:bg-gray-100">Delete</button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>

<!-- Modal untuk Create Transaction -->
<div id="createTransactionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Add New Transaction</h2>
        <form action="{{ route('transactions.store') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label for="date" class="block text-sm font-medium text-gray-700">Date:</label>
                <input type="date" name="date" id="date" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- Dropdown Kategori dengan Pencarian -->
            <div class="mb-4">
                <label for="category_id" class="block text-sm font-medium text-gray-700">Kategori</label>
                <div class="relative">
                    <button id="dropdownSearchButton" data-dropdown-toggle="dropdownSearch" class="w-full flex justify-between items-center px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" type="button">
                        <span id="selectedCategory">Pilih Kategori</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <!-- Dropdown Menu -->
                    <div id="dropdownSearch" class="z-10 hidden bg-white rounded-lg shadow w-full max-h-60 overflow-y-auto">
                        <div class="p-3">
                            <input type="text" id="searchCategoryCreate" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Cari kategori...">
                        </div>
                        <ul class="text-sm text-gray-700" aria-labelledby="dropdownSearchButton">
                            @foreach ($categories as $category)
                                <li>
                                    <a href="#" data-value="{{ $category->id }}" class="block px-4 py-2 hover:bg-gray-100">
                                        {{ $category->name }} ({{ ucfirst($category->type) }})
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <input type="hidden" name="category_id" id="category_id" value="">
            </div>
            <div class="mb-4">
                <label for="description" class="block text-sm font-medium text-gray-700">Description:</label>
                <input type="text" name="description" id="description" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="type" class="block text-sm font-medium text-gray-700">Type:</label>
                <select name="type" id="type" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="amount" class="block text-sm font-medium text-gray-700">Amount:</label>
                <input type="number" name="amount" id="amount" step="0.01" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeCreateModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-300 mr-2">Cancel</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">Add Transaction</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal untuk Edit Transaction -->
<div id="editTransactionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Edit Transaction</h2>
        <form id="editTransactionForm" method="POST">
            @csrf
            @method('PUT') <!-- Method Spoofing untuk Update -->
            <div class="mb-4">
                <label for="editDate" class="block text-sm font-medium text-gray-700">Date:</label>
                <input type="date" name="date" id="editDate" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>

            <!-- Dropdown Kategori dengan Pencarian -->
            <div class="mb-4">
                <label for="editCategoryId" class="block text-sm font-medium text-gray-700">Kategori</label>
                <div class="relative">
                    <button id="editDropdownSearchButton" data-dropdown-toggle="editDropdownSearch" class="w-full flex justify-between items-center px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" type="button">
                        <span id="editSelectedCategory">Pilih Kategori</span>
                        <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    <!-- Dropdown Menu -->
                    <div id="editDropdownSearch" class="z-10 hidden bg-white rounded-lg shadow w-full max-h-60 overflow-y-auto">
                        <div class="p-3">
                            <input type="text" id="editSearchCategory" class="w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" placeholder="Cari kategori...">
                        </div>
                        <ul class="text-sm text-gray-700" aria-labelledby="editDropdownSearchButton">
                            @foreach ($categories as $category)
                                <li>
                                    <a href="#" data-value="{{ $category->id }}" class="block px-4 py-2 hover:bg-gray-100">
                                        {{ $category->name }} ({{ ucfirst($category->type) }})
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <input type="hidden" name="category_id" id="editCategoryId" value="">
            </div>

            <div class="mb-4">
                <label for="editDescription" class="block text-sm font-medium text-gray-700">Description:</label>
                <input type="text" name="description" id="editDescription" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="editType" class="block text-sm font-medium text-gray-700">Type:</label>
                <select name="type" id="editType" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>
            <div class="mb-4">
                <label for="editAmount" class="block text-sm font-medium text-gray-700">Amount:</label>
                <input type="number" name="amount" id="editAmount" step="0.01" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeEditModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-300 mr-2">Cancel</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">Update Transaction</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Konfirmasi Delete -->
<div id="deleteConfirmationModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Confirm Delete</h2>
        <p class="mb-4">Are you sure you want to delete this transaction?</p>
        <div class="flex justify-end">
            <button onclick="closeDeleteModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-300 mr-2">Cancel</button>
            <form id="deleteForm" method="POST" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition duration-300">Delete</button>
            </form>
        </div>
    </div>
</div>

<!-- Modal untuk Input Kategori -->
<div id="categoryModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
    <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
        <h2 class="text-xl font-bold mb-4">Tambah Kategori Baru</h2>
        <form id="categoryForm" method="POST" action="{{ route('categories.store') }}">
            @csrf
            <div class="mb-4">
                <label for="categoryName" class="block text-sm font-medium text-gray-700">Nama Kategori:</label>
                <input type="text" name="name" id="categoryName" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label for="categoryType" class="block text-sm font-medium text-gray-700">Jenis Kategori:</label>
                <select name="type" id="categoryType" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" required>
                    <option value="income">Income</option>
                    <option value="expense">Expense</option>
                </select>
            </div>
            <div class="flex justify-end">
                <button type="button" onclick="closeCategoryModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-300 mr-2">Batal</button>
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">Simpan</button>
            </div>
        </form>
    </div>
</div>

<!-- Script untuk Mengontrol Modal -->
<script>
    function openCreateModal() {
        document.getElementById('createTransactionModal').classList.remove('hidden');
    }

    function closeCreateModal() {
        document.getElementById('createTransactionModal').classList.add('hidden');
    }

    // Tutup modal saat mengklik di luar modal
    document.getElementById('createTransactionModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeCreateModal();
        }
    });

    // Fungsi untuk menutup dropdown tertentu
    function CloseThisDropdown(dropdownId) {
        const dropdownMenu = document.getElementById(dropdownId);
        if (!dropdownMenu.classList.contains('hidden')) {
            dropdownMenu.classList.add('hidden');
        }
    }

    // Fungsi untuk menutup semua dropdown kecuali yang sedang dibuka
    function CloseOtherDropdown(currentDropdownId) {
        const dropdowns = document.querySelectorAll('.dropdown-menu');
        dropdowns.forEach(dropdown => {
            if (dropdown.id !== currentDropdownId && !dropdown.classList.contains('hidden')) {
                dropdown.classList.add('hidden');
            }
        });
    }

    $toggleDropdown = false;
    // Fungsi untuk toggle dropdown
    function toggleDropdown(dropdownId) {
        CloseOtherDropdown(dropdownId); // Tutup dropdown lain sebelum membuka yang baru
        const dropdownMenu = document.getElementById(dropdownId);
        dropdownMenu.classList.toggle('hidden');
        $toggleDropdown = true;
    }

    // Fungsi untuk membuka modal edit dan mengisi data
    function openEditModal(id, date, categoryId, description, type, amount) {
        // Isi form dengan data transaksi
        document.getElementById('editDate').value = date;
        document.getElementById('editDescription').value = description;
        document.getElementById('editType').value = type;
        document.getElementById('editAmount').value = amount;

        // Set action form untuk update
        document.getElementById('editTransactionForm').action = `/transactions/${id}`;

        // Set nilai default untuk kategori
        const selectedCategory = document.querySelector(`#editDropdownSearch ul li a[data-value="${categoryId}"]`);
        if (selectedCategory) {
            document.getElementById('editCategoryId').value = categoryId;
            document.getElementById('editSelectedCategory').textContent = selectedCategory.textContent;
        }

        // Tampilkan modal
        document.getElementById('editTransactionModal').classList.remove('hidden');
    }

    // Fungsi untuk menutup modal edit
    function closeEditModal() {
        document.getElementById('editTransactionModal').classList.add('hidden');
    }

    // Tutup modal saat mengklik di luar modal
    document.getElementById('editTransactionModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeEditModal();
        }
    });
    // Fungsi untuk membuka modal konfirmasi delete
    function confirmDelete(transactionId) {
        // Set action form untuk delete
        document.getElementById('deleteForm').action = `/transactions/${transactionId}`;

        // Tampilkan modal
        document.getElementById('deleteConfirmationModal').classList.remove('hidden');
        CloseThisDropdown(`dropdown-${id}`);
    }

    // Fungsi untuk menutup modal konfirmasi delete
    function closeDeleteModal() {
        document.getElementById('deleteConfirmationModal').classList.add('hidden');
    }

    // Tutup modal saat mengklik di luar modal
    document.getElementById('deleteConfirmationModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeDeleteModal();
        }
    });

    // Fungsi untuk membuka modal kategori
    function openCategoryModal() {
        document.getElementById('categoryModal').classList.remove('hidden');
    }

    // Fungsi untuk menutup modal kategori
    function closeCategoryModal() {
        document.getElementById('categoryModal').classList.add('hidden');
    }

    // Tutup modal saat mengklik di luar modal
    document.getElementById('categoryModal').addEventListener('click', function(event) {
        if (event.target === this) {
            closeCategoryModal();
        }
    });

    //     document.getElementById('categoryForm').addEventListener('submit', function(event) {
    //     event.preventDefault();

    //     fetch(this.action, {
    //         method: 'POST',
    //         body: new FormData(this),
    //         headers: {
    //             'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    //         },
    //     })
    //     .then(response => response.json())
    //     .then(data => {
    //         if (data.success) {
    //             // Tambahkan kategori baru ke dropdown
    //             const categorySelect = document.getElementById('category_id');
    //             const newOption = document.createElement('option');
    //             newOption.value = data.category.id;
    //             newOption.text = `${data.category.name} (${data.category.type})`;
    //             categorySelect.appendChild(newOption);

    //             // Pilih kategori yang baru ditambahkan
    //             categorySelect.value = data.category.id;

    //             // Tutup modal kategori
    //             closeCategoryModal();
    //         }
    //     })
    //     .catch(error => console.error('Error:', error));
    // });

    // Fungsi untuk memfilter kategori di modal Create
// Fungsi untuk memfilter kategori di dropdown
document.getElementById('searchCategoryCreate').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const categoryItems = document.querySelectorAll('#dropdownSearch ul li');

    categoryItems.forEach(item => {
        const categoryName = item.textContent.toLowerCase();
        if (categoryName.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});

// Fungsi untuk memilih kategori
document.querySelectorAll('#dropdownSearch ul li a').forEach(item => {
    item.addEventListener('click', function(event) {
        event.preventDefault();
        const selectedValue = this.getAttribute('data-value');
        const selectedText = this.textContent;

        // Set nilai yang dipilih ke input tersembunyi
        document.getElementById('category_id').value = selectedValue;

        // Tampilkan teks kategori yang dipilih
        document.getElementById('selectedCategory').textContent = selectedText;

        // Tutup dropdown
        document.getElementById('dropdownSearch').classList.add('hidden');
    });
});

// Fungsi untuk memfilter kategori di dropdown Edit
document.getElementById('editSearchCategory').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    const categoryItems = document.querySelectorAll('#editDropdownSearch ul li');

    categoryItems.forEach(item => {
        const categoryName = item.textContent.toLowerCase();
        if (categoryName.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
});

// Fungsi untuk memilih kategori di modal Edit
document.querySelectorAll('#editDropdownSearch ul li a').forEach(item => {
    item.addEventListener('click', function(event) {
        event.preventDefault();
        const selectedValue = this.getAttribute('data-value');
        const selectedText = this.textContent;

        // Set nilai yang dipilih ke input tersembunyi
        document.getElementById('editCategoryId').value = selectedValue;

        // Tampilkan teks kategori yang dipilih
        document.getElementById('editSelectedCategory').textContent = selectedText;

        // Tutup dropdown
        document.getElementById('editDropdownSearch').classList.add('hidden');
    });
});
</script>
@endsection