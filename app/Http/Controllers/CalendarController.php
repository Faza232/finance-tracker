<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class CalendarController extends Controller
{
    // Menampilkan halaman kalender
    public function index()
    {
        $query = Transaction::where('user_id', auth()->id())
            ->selectRaw('DATE(date) as date, type, SUM(amount) as total')
            ->groupBy('date', 'type')
            ->orderBy('date');
        
        $incomeData = (clone $query)->where('type', 'income')->get();
        $expenseData = (clone $query)->where('type', 'expense')->get();
        return view('calendar.index', compact(
            'incomeData',
            'expenseData',
        ));
    }

    // Mengambil transaksi berdasarkan tanggal
    public function getTransactionsByDate($date)
    {
        $transactions = Transaction::where('user_id', auth()->id())
            ->whereDate('date', $date)
            ->get();

        return response()->json($transactions);
    }
}