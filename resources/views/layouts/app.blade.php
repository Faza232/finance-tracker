<!DOCTYPE html>
<html lang="en">
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <title>Finance Tracker</title>
    @vite('resources/css/app.css') <!-- Load Tailwind CSS -->
</head>
<body class="bg-gray-100">
    <div class="flex flex-col h-screen">
        <!-- Navbar -->
        <nav id="navbar" class="fixed top-0 left-0 w-full bg-blue-200 border-gray-200 z-30 transition-all duration-300 ease-in-out">
            <div class="max-w-screen-xl flex flex-wrap items-center mx-5 p-4">
                <!-- Tombol Hamburger untuk Semua Layar -->
                <button id="sidebarToggle" class="p-2 bg-white rounded-lg shadow-lg">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
                <span class="mx-2 self-center text-2xl font-semibold whitespace-nowrap">Ini Navbar</span>
            </div>
        </nav>

        <!-- Main Content and Sidebar -->
        <div class="flex flex-1">
            <!-- Sidebar -->
            <nav id="sidebar" class="fixed top-0 left-0 bg-white w-64 h-full shadow-lg text-lg text-gray-700 z-20 transform -translate-x-full transition-transform duration-300 ease-in-out">
                <h2 class="px-4 text-xl font-semibold text-gray-800">Ini Sidebar</h2>
                <a href="{{ route('transactions.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-100 hover:text-blue-600 transition duration-300">
                    <svg class="w-7 h-7 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                    </svg>
                    Dashboard
                </a>
                <a href="{{ route('transactions.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-100 hover:text-blue-600 transition duration-300">
                    <svg class="w-7 h-7 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Transactions
                </a>
                <a href="{{ route('categories.index') }}" class="flex items-center px-4 py-3 hover:bg-gray-100 hover:text-blue-600 transition duration-300">
                    <svg class="w-7 h-7 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Categories
                </a>
                <a href="{{ route('reports.index')}}" class="flex items-center px-4 py-3 hover:bg-gray-100 hover:text-blue-600 transition duration-300">
                    <svg class="w-7 h-7 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Reports
                </a>
                <a href="{{ route('forecast.index')}}" class="flex items-center px-4 py-3 hover:bg-gray-100 hover:text-blue-600 transition duration-300">
                    <svg class="w-7 h-7 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                    Forecasts
                </a>
                @auth
                    <form action="{{ route('logout') }}" method="POST" class="flex items-center px-4 py-3 hover:bg-gray-100 hover:text-blue-600 transition duration-300">
                        @csrf
                        <button type="submit" class="flex items-center">
                            <svg class="w-7 h-7 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                            </svg>
                            Logout
                        </button>
                    </form>
                @endauth
            </nav>

            <!-- Main Content -->
            <div id="mainContent" class="flex-1 p-4 mt-16 transition-all duration-300 xl:ease-in-out">
                <!-- Konten Halaman -->
                <div class="max-w-screen-xl mx-auto">
                    @yield('content') <!-- Konten halaman akan diisi di sini -->
                </div>
            </div>
        </div>
    </div>

    <!-- Script untuk Toggle Sidebar -->
    <script>
        const sidebar = document.getElementById('sidebar');
        const navbar = document.getElementById('navbar');
        const mainContent = document.getElementById('mainContent');
        const sidebarToggle = document.getElementById('sidebarToggle');

        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('-translate-x-full');
            sidebar.classList.toggle('translate-x-0');

            // Sesuaikan posisi navbar dan konten utama
            if (sidebar.classList.contains('translate-x-0')) {
                navbar.style.left = '16rem'; // Sesuaikan dengan lebar sidebar
                mainContent.style.marginLeft = '16rem'; // Sesuaikan dengan lebar sidebar
            } else {
                navbar.style.left = '0';
                mainContent.style.marginLeft = '0';
            }
        });
    </script>
</body>
</html>