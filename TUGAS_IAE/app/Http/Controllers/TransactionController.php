<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('transactions.index', compact('transactions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|in:income,expense',
            'category' => 'required|string',
            'description' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'icon' => 'nullable|string'
        ]);

        $transaction = Transaction::create([
            'user_id' => Auth::id(),
            'type' => $request->type,
            'category' => $request->category,
            'description' => $request->description,
            'amount' => $request->amount,
            'icon' => $request->icon ?? '💳'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Transaction recorded!',
            'transaction' => $transaction
        ]);
    }
}