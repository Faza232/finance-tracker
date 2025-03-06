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
    
        // Query dasar untuk income dan expense
        $query = Transaction::where('user_id', auth()->id())
            ->whereYear('date', $selectedYear)
            ->when($filterType == 'monthly', function ($query) use ($selectedMonth) {
                return $query->whereMonth('date', $selectedMonth);
            });
    
        // Hitung total income dan expense
        $totalIncome = (clone $query)->where('type', 'income')->sum('amount');
        $totalExpense = (clone $query)->where('type', 'expense')->sum('amount');
    
        // Ambil data untuk diagram
        $incomeData = (clone $query)
            ->where('type', 'income')
            ->selectRaw('DATE(date) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();
    
        $expenseData = (clone $query)
            ->where('type', 'expense')
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