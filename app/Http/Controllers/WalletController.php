<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WalletController extends Controller
{
    public function update(Request $request)
    {
        if ($request->action == 'yes') {
            // Simpan saldo berlebih (bisa di session atau database)
            session(['overpaid_balance' => $request->overpaid_amount]);

            return redirect()->route('dashboard')->with('message', 'Balance stored successfully!');
        }

        // Jika pengguna memilih "No"
        return redirect()->route('payment.index')->with('error', 'Please retry with the correct payment amount.');
    }


}
