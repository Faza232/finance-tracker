@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold text-center mb-6">Kalender Transaksi</h1>

    <!-- Kalender -->
    <div id="calendar" class="bg-white rounded-lg shadow-md p-4"></div>

    <!-- Modal Rincian Transaksi -->
    <div id="transactionModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center hidden">
        <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-md">
            <h2 class="text-xl font-bold mb-4">Rincian Transaksi</h2>
            <div id="transactionDetails" class="mb-4">
                <!-- Rincian transaksi akan ditampilkan di sini -->
            </div>
            <div class="flex justify-end">
                <button onclick="closeTransactionModal()" class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition duration-300">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Script untuk FullCalendar -->
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/core@6.1.9/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/daygrid@6.1.9/index.global.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@fullcalendar/interaction@6.1.9/index.global.min.js"></script>

<!-- Script untuk Mengontrol Kalender dan Modal -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const calendarEl = document.getElementById('calendar');
        const calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: '/calendar/transactions',
            dateClick: function(info) {
                fetch(`/calendar/transactions/${info.dateStr}`)
                    .then(response => response.json())
                    .then(data => {
                        const transactionDetails = document.getElementById('transactionDetails');
                        transactionDetails.innerHTML = '';

                        if (data.length > 0) {
                            data.forEach(transaction => {
                                const transactionItem = document.createElement('div');
                                transactionItem.className = 'mb-2 p-2 border-b';
                                transactionItem.innerHTML = `
                                    <p><strong>Deskripsi:</strong> ${transaction.description}</p>
                                    <p><strong>Jenis:</strong> ${transaction.type}</p>
                                    <p><strong>Jumlah:</strong> Rp${transaction.amount.toLocaleString()}</p>
                                `;
                                transactionDetails.appendChild(transactionItem);
                            });
                        } else {
                            transactionDetails.innerHTML = '<p>Tidak ada transaksi pada tanggal ini.</p>';
                        }

                        // Tampilkan modal
                        document.getElementById('transactionModal').classList.remove('hidden');
                    });
            },
            eventContent: function(arg) {
                const income = arg.event.extendedProps.income || 0;
                const expense = arg.event.extendedProps.expense || 0;

                return {
                    html: `
                        <div class="text-center">
                            <p class="text-green-600">+Rp${income.toLocaleString()}</p>
                            <p class="text-red-600">-Rp${expense.toLocaleString()}</p>
                        </div>
                    `
                };
            }
        });

        calendar.render();
    });

    // Fungsi untuk menutup modal rincian transaksi
    function closeTransactionModal() {
        document.getElementById('transactionModal').classList.add('hidden');
    }
</script>
@endsection