<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaction;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;
use Carbon\Carbon;

class ForecastController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter filter dari request
        $type = $request->get('type', 'total_balance'); // Default: total_balance
        $numberOfMonths = $request->get('number_of_months', 6); // Default: 6 bulan

        // Ambil data transaksi
        $transactions = Transaction::orderBy('date')->get(['date', 'type', 'amount']);

        // Siapkan data untuk forecasting
        $data = [];
        $labels = [];
        $currentBalance = 0;

        // Hitung total balance atau cash flow berdasarkan tipe filter
        foreach ($transactions as $transaction) {
            $month = Carbon::parse($transaction->date)->format('Y-m'); // Konversi ke Carbon
            if (!isset($data[$month])) {
                $data[$month] = 0;
                $labels[] = $month;
            }

            if ($type == 'total_balance') {
                // Total Balance Over Time: Akumulasi saldo dari waktu ke waktu
                $currentBalance += ($transaction->type == 'income' ? $transaction->amount : -$transaction->amount);
                $data[$month] = $currentBalance;
            } elseif ($type == 'cash_flow') {
                // Cash Flow Over Time: Total pemasukan dan pengeluaran per bulan
                $data[$month] += ($transaction->type == 'income' ? $transaction->amount : -$transaction->amount);
            }
        }

        // Simpan data ke file CSV untuk forecasting
        $csvPath = storage_path('app/transactions.csv');
        $handle = fopen($csvPath, 'w');
        fputcsv($handle, ['date', 'amount']);
        foreach ($data as $month => $amount) {
            fputcsv($handle, [$month, $amount]);
        }
        fclose($handle);

        // Jalankan script Python untuk forecasting ARIMA
        $forecastOutputPath = storage_path('app/forecast.csv');
        $process = new Process([
            'python3',
            base_path('scripts/arima_forecast.py'), // Path ke script Python
            $csvPath, // Input data transaksi
            $numberOfMonths, // Jumlah bulan untuk forecasting
            $forecastOutputPath, // Output hasil forecasting
        ]);
        $process->run();
        //Jalankan script Python untuk forecasting ARIMA
        $forecastOutputPath = storage_path('app/forecast.csv');
        $process = new Process([
            'C:\laragon\www\finance-tracker\venv\Scripts\python.exe', // Path ke Python di venv
            'C:\laragon\www\finance-tracker\scripts\arima_forecast.py', // Path ke script Python
            'C:\laragon\www\finance-tracker\storage\app\transactions.csv', // Path ke file input CSV                         
            $numberOfMonths,                             
            'C:\laragon\www\finance-tracker\storage\app\forecast.csv',                     
        ]);
        $process->run();

        // Cek jika proses gagal
        if (!$process->isSuccessful()) {
            throw new ProcessFailedException($process);
        }

        // Baca hasil forecasting dari file CSV
        $forecastData = array_map('str_getcsv', file($forecastOutputPath));
        array_shift($forecastData); // Hapus header

        // Siapkan data untuk chart
        $forecastLabels = [];
        $forecastValues = [];
        foreach ($forecastData as $row) {
            $forecastLabels[] = $row[0]; // Tanggal
            $forecastValues[] = $row[1]; // Nilai forecast
        }

        return view('forecast.index', compact('forecastLabels', 'forecastValues', 'type', 'numberOfMonths'));
    }
}
        //Jalankan script Python untuk forecasting ARIMA
        // $forecastOutputPath = storage_path('app/forecast.csv');
        // $process = new Process([
        //     'C:\laragon\www\finance-tracker\venv\Scripts\python.exe', // Path ke Python di venv
        //     'C:\laragon\www\finance-tracker\scripts\arima_forecast.py', // Path ke script Python
        //     'C:\laragon\www\finance-tracker\storage\app\transactions.csv', // Path ke file input CSV                         
        //     $numberOfMonths,                             
        //     'C:\laragon\www\finance-tracker\storage\app\forecast.csv',                     
        // ]);
        // $process->run();