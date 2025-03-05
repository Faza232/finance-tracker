<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Category;
use Illuminate\Http\Request;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter filter dari request
        $type = $request->get('type'); // Jenis transaksi (income/expense)
        $startDate = $request->get('start_date'); // Tanggal awal
        $endDate = $request->get('end_date'); // Tanggal akhir

        // Query dasar untuk transaksi
        $transactions = Transaction::query();

        // Filter berdasarkan jenis transaksi
        if ($type && in_array($type, ['income', 'expense'])) {
            $transactions->where('type', $type);
        }

        // Filter berdasarkan rentang tanggal
        if ($startDate && $endDate) {
            $transactions->whereBetween('date', [$startDate, $endDate]);
        }

        // Ambil data transaksi
        $transactions = $transactions->orderBy('date', 'desc')->get();

        // Hitung saldo berdasarkan transaksi yang difilter
        $balance = $transactions->sum(function($transaction) {
            return $transaction->type == 'income' ? $transaction->amount : -$transaction->amount;
        });
        $categories = Category::where('user_id', auth()->id())->get();
        return view('transactions.index', compact('transactions', 'balance', 'type', 'startDate', 'endDate', 'categories'));
    }

    public function report()
    {
        // Data untuk diagram
        $incomeData = Transaction::where('type', 'income')
            ->selectRaw('DATE(date) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $expenseData = Transaction::where('type', 'expense')
            ->selectRaw('DATE(date) as date, SUM(amount) as total')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return view('reports.index', compact('transactions', 'balance', 'incomeData', 'expenseData'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'new_category' => 'nullable|string|max:255',
        ]);
    
        // Jika kategori baru diinput, buat kategori baru
        if ($request->new_category) {
            $category = Category::create([
                'name' => $request->new_category,
                'type' => $request->type,
                'user_id' => auth()->id(), // Tambahkan user_id
            ]);
            $category_id = $category->id;
        } else {
            $category_id = $request->category_id;
        }
    
        // Simpan transaksi
        Transaction::create([
            'date' => $request->date,
            'description' => $request->description,
            'type' => $request->type,
            'amount' => $request->amount,
            'category_id' => $category_id,
            'user_id' => auth()->id(), // Tambahkan user_id
        ]);
    
        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }


    public function update(Request $request, Transaction $transaction)
    {
        $request->validate([
            'date' => 'required|date',
            'description' => 'required|string|max:255',
            'type' => 'required|in:income,expense',
            'amount' => 'required|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
            'new_category' => 'nullable|string|max:255',
        ]);
    
        // Jika kategori baru diinput, buat kategori baru
        if ($request->new_category) {
            $category = Category::create([
                'name' => $request->new_category,
                'type' => $request->type,
                'user_id' => auth()->id(), // Tambahkan user_id
            ]);
            $category_id = $category->id;
        } else {
            $category_id = $request->category_id;
        }
    
        // Update transaksi
        $transaction->update([
            'date' => $request->date,
            'description' => $request->description,
            'type' => $request->type,
            'amount' => $request->amount,
            'category_id' => $category_id,
        ]);
    
        return redirect()->route('transactions.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy(Transaction $transaction)
    {
        $transaction->delete();
        return redirect()->route('transactions.index')->with('success', 'Transaction deleted successfully.');
    }
}
