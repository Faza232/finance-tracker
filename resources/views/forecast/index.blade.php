@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold text-center mb-6">Forecasting Chart (ARIMA)</h1>

    <!-- Form Filter -->
    <form action="{{ route('forecast.index') }}" method="GET" class="mb-6 bg-white p-4 rounded-lg shadow-md">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <!-- Filter Tipe Forecast -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700">Tipe Forecast</label>
                <select name="type" id="type" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    <option value="total_balance" {{ $type == 'total_balance' ? 'selected' : '' }}>Total Balance Over Time</option>
                    <option value="cash_flow" {{ $type == 'cash_flow' ? 'selected' : '' }}>Cash Flow Over Time</option>
                </select>
            </div>

            <!-- Filter Jumlah Bulan -->
            <div>
                <label for="number_of_months" class="block text-sm font-medium text-gray-700">Jumlah Bulan</label>
                <select name="number_of_months" id="number_of_months" class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500">
                    @for ($i = 3; $i <= 12; $i++)
                        <option value="{{ $i }}" {{ $numberOfMonths == $i ? 'selected' : '' }}>{{ $i }} Bulan</option>
                    @endfor
                </select>
            </div>

            <!-- Tombol Filter -->
            <div class="flex items-end">
                <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition duration-300">Filter</button>
            </div>
        </div>
    </form>

    <!-- Chart Forecasting -->
    <div class="mt-8">
        <h2 class="text-2xl font-bold mb-4">Forecasting {{ $type == 'total_balance' ? 'Total Balance' : 'Cash Flow' }} for {{ $numberOfMonths }} Months</h2>
        <div class="w-full h-96"> <!-- Tinggi chart -->
            <canvas id="forecastChart"></canvas>
        </div>
    </div>
</div>

<!-- Script untuk Chart -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const labels = @json($forecastLabels);
        const data = @json($forecastValues);

        const config = {
            type: 'line', // Jenis chart (line, bar, dll)
            data: {
                labels: labels,
                datasets: [{
                    label: '{{ $type == 'total_balance' ? 'Total Balance' : 'Cash Flow' }}',
                    data: data,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: {{ $type == 'cash_flow' ? 'true' : 'false' }}
                    }
                },
                responsive: true,
                maintainAspectRatio: false
            }
        };

        const forecastChart = new Chart(
            document.getElementById('forecastChart'),
            config
        );
    });
</script>
@endsection