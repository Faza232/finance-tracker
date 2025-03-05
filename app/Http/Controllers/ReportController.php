<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter filter dari request
        $filterType = $request->get('filterType', 'yearly'); // Default: yearly
        $selectedYear = $request->get('year', date('Y')); // Default: tahun sekarang
        $selectedMonth = $request->get('month', date('m')); // Default: bulan sekarang
        $incomeQuery;
        $expenseQuery;
        
        // Jika filterType = monthly, tambahkan filter bulan
        if ($filterType == 'monthly') {
            $incomeQuery = Transaction::where('user_id', auth()->id())
                ->where('type', 'income')
                ->whereYear('date', $selectedYear)-> whereMonth('date', $selectedMonth);
            $expenseQuery = Transaction::where('user_id', auth()->id())
                ->where('type', 'expense')
                ->whereYear('date', $selectedYear)-> whereMonth('date', $selectedMonth);
        }else{
            $incomeQuery = Transaction::where('user_id', auth()->id())
                ->where('type', 'income')
                ->whereYear('date', $selectedYear);
            $expenseQuery = Transaction::where('user_id', auth()->id())
                ->where('type', 'expense')
                ->whereYear('date', $selectedYear);
        }

        // Hitung total income dan total expense
        $totalIncome = $incomeQuery->sum('amount');
        $totalExpense = $expenseQuery->sum('amount');

        // Ambil data untuk diagram
        $incomeData = $incomeQuery
            ->selectRaw('DATE(date) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $expenseData = $expenseQuery
            ->selectRaw('DATE(date) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('reports.index', compact(
            'incomeData',
            'expenseData',
            'filterType',
            'selectedYear',
            'selectedMonth',
            'totalIncome',
            'totalExpense'
        ));
    }
}