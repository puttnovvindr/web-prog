<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        // Menyimpan harga registrasi tetap di session
        $registrationPrice = 125000; // Harga registrasi yang ditentukan

        return view('payment.index', ['registrationPrice' => $registrationPrice]);
    }

    public function store(Request $request)
    {
        // Mengambil harga dari session
        $registrationPrice = 125000; // Harga tetap yang digunakan

        // Validasi jumlah pembayaran
        $request->validate([
            'payment_amount' => ['required', 'numeric', 'min:' . $registrationPrice],
        ]);

        $paymentAmount = $request->payment_amount;

        if ($paymentAmount < $registrationPrice) {
            $underpaidAmount = $registrationPrice - $paymentAmount;
            return redirect()->back()->with('error', 'You are still underpaid ' . $underpaidAmount);
        }

        // Jika pembayaran lebih
        $overpaidAmount = $paymentAmount - $registrationPrice;
        return view('payment.result', [
            'overpaidAmount' => $overpaidAmount,
            'registrationPrice' => $registrationPrice,
        ]);
    }

    public function enterBalance(Request $request)
    {
        // Menambahkan saldo ke dompet pengguna
        $user = auth()->user();
        $user->wallet_balance += $request->overpaid_amount; // Masukkan jumlah ke saldo
        $user->save();

        return redirect()->route('dashboard')->with('success', 'Overpaid amount has been added to your wallet balance.');
    }

    public function retryPayment(Request $request)
    {
        // Kembali ke halaman pembayaran
        return redirect()->route('payment.index')->with('error', 'Please enter the correct payment amount.');
    }
}
