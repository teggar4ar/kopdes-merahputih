<?php

namespace App\Http\Controllers;

use App\Models\SavingsTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SavingsController extends Controller
{
    /**
     * Display the savings transaction history page.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Get all savings transactions for the user, paginated
        $transactions = SavingsTransaction::where('user_id', $user->id)
            ->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        // Calculate running balance for each transaction
        $this->calculateRunningBalances($transactions);

        return view('savings.index', compact('transactions'));
    }

    /**
     * Calculate running balances for transactions
     */
    private function calculateRunningBalances($transactions): void
    {
        $user = Auth::user();

        // Get all user transactions ordered by date (oldest first) for balance calculation
        $allTransactions = SavingsTransaction::where('user_id', $user->id)
            ->orderBy('transaction_date', 'asc')
            ->orderBy('created_at', 'asc')
            ->get();

        $balances = [
            'wajib' => 0,
            'pokok' => 0,
            'sukarela' => 0
        ];

        // Create a lookup array for balances at each transaction
        $balanceLookup = [];

        foreach ($allTransactions as $transaction) {
            // Only apply completed transactions to running balance
            if ($transaction->status === 'completed') {
                if ($transaction->transaction_type === 'setor') {
                    $balances[$transaction->savings_type] += $transaction->amount;
                } else {
                    $balances[$transaction->savings_type] -= $transaction->amount;
                }
            }

            // For pending transactions, show the balance before the transaction
            // For completed transactions, show the balance after the transaction
            if ($transaction->status === 'pending') {
                // For pending transactions, show balance before this transaction
                $balanceLookup[$transaction->id] = [
                    'balance' => $balances[$transaction->savings_type],
                    'total_balance' => array_sum($balances)
                ];
            } else {
                // For completed transactions, balance already includes this transaction
                $balanceLookup[$transaction->id] = [
                    'balance' => $balances[$transaction->savings_type],
                    'total_balance' => array_sum($balances)
                ];
            }
        }

        // Apply the calculated balances to the paginated transactions
        foreach ($transactions as $transaction) {
            $transaction->running_balance = $balanceLookup[$transaction->id]['balance'] ?? 0;
            $transaction->total_balance = $balanceLookup[$transaction->id]['total_balance'] ?? 0;
        }
    }
}
