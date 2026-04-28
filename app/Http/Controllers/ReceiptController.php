<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class ReceiptController extends Controller
{
    public function print(Transaction $transaction)
    {
        // Optional: Gate for authorization
        // Gate::authorize('view', $transaction);

        // Load all necessary relationships
        $transaction->load(['user', 'transactionable']);

        return view('receipt.print', ['transaction' => $transaction]);
    }
}
