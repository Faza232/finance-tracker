@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-3xl font-bold text-center mb-6">Reports</h1>

    <form action="{{ route('reports.index') }}" method="GET" class="mb-6">
        <div class="flex items-center space-x-4">
            <div>
                <label for="filterType" class="mr-2">Filter by:</label>
                <select name="filterType" id="filterType" class="px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                    <option value="yearly" {{ $filterType == 'yearly' ? 'selected' : '' }}>Yearly</option>
                    <option value="monthly" {{ $filterType == 'monthly' ? 'selected' : '' }}>Monthly</option>
                </select>
            </div>

            <div>
                <label for="year" class="mr-2">Year:</label>
                <select name="year" id="year" class="px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                    @for ($i = date('Y'); $i >= 2020; $i--)
                        <option value="{{ $i }}" {{ $selectedYear == $i ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>

            @if ($filterType == 'monthly')
                <div>
                    <label for="month" class="mr-2">Month:</label>
                    <select name="month" id="month" class="px-3 py-2 border border-gray-300 rounded-lg shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500" onchange="this.form.submit()">
                        @for ($i = 1; $i <= 12; $i++)
                            <option value="{{ $i }}" {{ $selectedMonth == $i ? 'selected' : '' }}>{{ date('F', mktime(0, 0, 0, $i, 10)) }}</option>
                        @endfor
                    </select>
                </div>
            @endif
        </div>
    </form>

    <div class="grid grid-cols-2 gap-4 mb-8">
        <div class="bg-green-100 p-4 rounded-lg shadow-md">
            <p class="text-lg font-semibold text-green-700">Total Income</p>
            <p class="text font-bold">Rp.{{ number_format($totalIncome, 2) }}</p>
        </div>
        <div class="bg-red-100 p-4 rounded-lg shadow-md">
            <p class="text-lg font-semibold text-red-700">Total Expense</p>
            <p class="text font-bold">Rp.{{ number_format($totalExpense, 2) }}</p>
        </div>
    </div>

    <div class="mt-8">
        <h2 class="text-2xl font-bold mb-4">Income and Expense Chart</h2>
        <div class="w-full h-96">
            <canvas id="incomeExpenseChart"></canvas>
        </div>
    </div>
    <div class="mt-8">
        <h2 class="text-2xl font-bold mb-4">Cash Flow Over Time</h2>
        <div class="w-full h-96">
            <canvas id="cashFlowChart"></canvas>
        </div>
    </div>
    <div class="mt-8">
        <h2 class="text-2xl font-bold mb-4">Total Balance Over Time</h2>
        <div class="w-full h-96">
            <canvas id="totalBalanceChart"></canvas>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const incomeData = @json($incomeData);
        const expenseData = @json($expenseData);

        const incomeDates = incomeData.map(income => income.date);
        const expenseDates = expenseData.map(expense => expense.date);
        const allDates = [...incomeDates, ...expenseDates];
        const uniqueDates = [...new Set(allDates)];
        const sortedDates = uniqueDates.sort((a, b) => new Date(a) - new Date(b));
        const labels = sortedDates;

        // Buat Map untuk income dan expense
        const incomeMap = new Map(incomeData.map(item => [item.date, item.total]));
        const expenseMap = new Map(expenseData.map(item => [item.date, item.total]));

        // Map data ke labels
        const incomeValues = labels.map(date => incomeMap.get(date) || 0);
        const expenseValues = labels.map(date => expenseMap.get(date) || 0);

        //hitung nilai cash flow
        const cashFlowValues = labels.map(date => {
            const income = incomeMap.get(date) || 0;
            const expense = expenseMap.get(date) || 0;
            return income - expense;
        });

        //Hitung total balance (kumulatif cash flow)
        let runningBalance = 0;
        const totalBalanceValues = cashFlowValues.map(value => {
            runningBalance += value;
            return runningBalance;
        });

        // Data untuk Barchart (Income dan Expense)
        const barData = {
            labels: labels,
            datasets: [
                {
                    label: 'Income',
                    data: incomeValues,
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                },
                {
                    label: 'Expense',
                    data: expenseValues,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }
            ]
        };

        // Konfigurasi Barchart
        const barConfig = {
            type: 'bar',
            data: barData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
            }
        };

        // Render Barchart
        const incomeExpenseChart = new Chart(
            document.getElementById('incomeExpenseChart'),
            barConfig
        );

        // Data untuk Line Chart (Cash Flow)
        const lineData = {
            labels: labels,
            datasets: [
                {
                    label: 'Cash Flow',
                    data: cashFlowValues,
                    borderColor: 'rgba(54, 162, 235, 1)',
                    backgroundColor: 'rgba(54, 162, 235, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4 // Memberikan efek kurva pada line chart
                }
            ]
        };

        // Konfigurasi Line Chart
        const lineConfig = {
            type: 'line',
            data: lineData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
            }
        };

        // Render Line Chart
        const cashFlowChart = new Chart(
            document.getElementById('cashFlowChart'),
            lineConfig
        );

        // Data untuk Line Chart (Total Balance)
        const totalBalanceData = {
            labels: labels,
            datasets: [
                {
                    label: 'Total Balance',
                    data: totalBalanceValues,
                    borderColor: 'rgba(153, 102, 255, 1)',
                    backgroundColor: 'rgba(153, 102, 255, 0.2)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4 // Memberikan efek kurva pada line chart
                }
            ]
        };

        // Konfigurasi Line Chart (Total Balance)
        const totalBalanceConfig = {
            type: 'line',
            data: totalBalanceData,
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                },
                responsive: true,
                maintainAspectRatio: false,
            }
        };

        // Render Line Chart (Total Balance)
        const totalBalanceChart = new Chart(
            document.getElementById('totalBalanceChart'),
            totalBalanceConfig
        );
    });

</script>
@endsection