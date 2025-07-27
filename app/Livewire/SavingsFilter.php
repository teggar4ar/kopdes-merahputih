<?php

namespace App\Livewire;

use App\Models\SavingsTransaction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class SavingsFilter extends Component
{
    use WithPagination;

    public $savingsType = '';
    public $transactionType = '';
    public $status = '';
    public $startDate = '';
    public $endDate = '';
    public $quickFilter = '';
    public $showFilters = false;

    protected $listeners = ['refreshSavingsData'];

    protected $queryString = [
        'savingsType',
        'transactionType',
        'status',
        'startDate',
        'endDate',
        'quickFilter',
        'showFilters',
        'page'
    ];

    public function mount()
    {
        // Set Carbon locale for Indonesian formatting
        Carbon::setLocale('id');

        // Set default date range to last 6 months if not set
        if (empty($this->startDate)) {
            $this->startDate = Carbon::now()->subMonths(6)->format('Y-m-d');
        }
        if (empty($this->endDate)) {
            // Include future months for mandatory savings payments
            $this->endDate = Carbon::now()->addMonths(12)->format('Y-m-d');
        }
    }

    public function updatedSavingsType()
    {
        $this->resetPage();
    }

    public function updatedTransactionType()
    {
        $this->resetPage();
    }

    public function updatedStatus()
    {
        $this->resetPage();
    }

    public function updatedStartDate()
    {
        $this->resetPage();
    }

    public function updatedEndDate()
    {
        $this->resetPage();
    }

    public function updatedQuickFilter()
    {
        $this->applyQuickFilter();
        $this->resetPage();
    }

    public function applyQuickFilter()
    {
        switch ($this->quickFilter) {
            case 'recent_withdrawals':
                $this->savingsType = 'sukarela';
                $this->transactionType = 'tarik';
                $this->status = '';
                $this->startDate = Carbon::now()->subMonths(3)->format('Y-m-d');
                $this->endDate = Carbon::now()->format('Y-m-d');
                break;

            case 'pending_transactions':
                $this->savingsType = '';
                $this->transactionType = '';
                $this->status = 'pending';
                $this->startDate = Carbon::now()->subMonths(6)->format('Y-m-d');
                $this->endDate = Carbon::now()->format('Y-m-d');
                break;

            case 'sukarela_deposits':
                $this->savingsType = 'sukarela';
                $this->transactionType = 'setor';
                $this->status = 'completed';
                $this->startDate = Carbon::now()->subMonths(12)->format('Y-m-d');
                $this->endDate = Carbon::now()->format('Y-m-d');
                break;

            case 'monthly_principal':
                $this->savingsType = 'pokok';
                $this->transactionType = 'setor';
                $this->status = 'completed';
                $this->startDate = Carbon::now()->subMonths(12)->format('Y-m-d');
                $this->endDate = Carbon::now()->format('Y-m-d');
                break;

            case 'this_month':
                $this->savingsType = '';
                $this->transactionType = '';
                $this->status = '';
                $this->startDate = Carbon::now()->startOfMonth()->format('Y-m-d');
                $this->endDate = Carbon::now()->endOfMonth()->format('Y-m-d');
                break;

            case 'last_30_days':
                $this->savingsType = '';
                $this->transactionType = '';
                $this->status = '';
                $this->startDate = Carbon::now()->subDays(30)->format('Y-m-d');
                $this->endDate = Carbon::now()->addMonths(12)->format('Y-m-d');
                break;

            case 'all_completed':
                $this->savingsType = '';
                $this->transactionType = '';
                $this->status = 'completed';
                $this->startDate = Carbon::now()->subMonths(12)->format('Y-m-d');
                $this->endDate = Carbon::now()->addMonths(12)->format('Y-m-d');
                break;

            default:
                // Reset to default if empty or invalid
                if (empty($this->quickFilter)) {
                    $this->savingsType = '';
                    $this->transactionType = '';
                    $this->status = '';
                    $this->startDate = Carbon::now()->subMonths(6)->format('Y-m-d');
                    $this->endDate = Carbon::now()->addMonths(12)->format('Y-m-d');
                }
                break;
        }
    }

    public function toggleFilters()
    {
        $this->showFilters = !$this->showFilters;
    }

    public function clearFilters()
    {
        $this->quickFilter = '';
        $this->savingsType = '';
        $this->transactionType = '';
        $this->status = '';
        $this->startDate = Carbon::now()->subMonths(6)->format('Y-m-d');
        $this->endDate = Carbon::now()->addMonths(12)->format('Y-m-d');
        $this->resetPage();
    }

    public function refreshSavingsData()
    {
        // This method will be called when withdrawal is successful
        // Force refresh of the component
        $this->render();
    }

    public function getFilteredTransactionsProperty()
    {
        $user = Auth::user();

        $query = SavingsTransaction::where('user_id', $user->id);

        // Apply filters
        if (!empty($this->savingsType)) {
            $query->where('savings_type', $this->savingsType);
        }

        if (!empty($this->transactionType)) {
            $query->where('transaction_type', $this->transactionType);
        }

        if (!empty($this->status)) {
            $query->where('status', $this->status);
        }

        if (!empty($this->startDate)) {
            // Ensure we capture transactions from the start of the day in local timezone
            $startOfDay = Carbon::parse($this->startDate)->startOfDay();
            $query->where('transaction_date', '>=', $startOfDay);
        }

        if (!empty($this->endDate)) {
            // Ensure we capture all transactions for the end date in local timezone
            $endOfDay = Carbon::parse($this->endDate)->endOfDay();
            $query->where('transaction_date', '<=', $endOfDay);
        }

        $transactions = $query->orderBy('transaction_date', 'desc')
            ->orderBy('created_at', 'desc')
            ->orderBy('id', 'desc') // Additional sorting by ID for consistency
            ->paginate(15);

        // Calculate running balances
        $this->calculateRunningBalances($transactions);

        return $transactions;
    }

    private function calculateRunningBalances($transactions)
    {
        $user = Auth::user();

        // Get all user transactions ordered by date and creation time for proper chronological order
        $allTransactions = SavingsTransaction::where('user_id', $user->id)
            ->orderBy('transaction_date', 'asc')
            ->orderBy('created_at', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $balances = [
            'wajib' => 0,
            'pokok' => 0,
            'sukarela' => 0
        ];

        $balanceLookup = [];

        foreach ($allTransactions as $transaction) {
            // Calculate running balance up to this point (before this transaction)
            $balanceBeforeTransaction = [
                'wajib' => $balances['wajib'],
                'pokok' => $balances['pokok'],
                'sukarela' => $balances['sukarela']
            ];

            // Only apply completed transactions to running balance
            if ($transaction->status === 'completed') {
                if ($transaction->transaction_type === 'setor') {
                    $balances[$transaction->savings_type] += $transaction->amount;
                } else {
                    $balances[$transaction->savings_type] -= $transaction->amount;
                }
            }

            // For pending transactions, show the current balance (what it will be after this transaction)
            // For completed transactions, show the balance after the transaction
            if ($transaction->status === 'pending') {
                // Calculate what the balance would be if this transaction was completed
                $projectedBalance = $balanceBeforeTransaction[$transaction->savings_type];
                if ($transaction->transaction_type === 'setor') {
                    $projectedBalance += $transaction->amount;
                } else {
                    $projectedBalance -= $transaction->amount;
                }

                $balanceLookup[$transaction->id] = [
                    'balance' => $projectedBalance,
                    'total_balance' => array_sum($balanceBeforeTransaction) +
                        ($transaction->transaction_type === 'setor' ? $transaction->amount : -$transaction->amount),
                    'is_pending' => true
                ];
            } else {
                // For completed transactions, balance already includes this transaction
                $balanceLookup[$transaction->id] = [
                    'balance' => $balances[$transaction->savings_type],
                    'total_balance' => array_sum($balances),
                    'is_pending' => false
                ];
            }
        }

        // Apply the calculated balances to the paginated transactions
        foreach ($transactions as $transaction) {
            $transaction->running_balance = $balanceLookup[$transaction->id]['balance'] ?? 0;
            $transaction->total_balance = $balanceLookup[$transaction->id]['total_balance'] ?? 0;
            $transaction->is_pending_balance = $balanceLookup[$transaction->id]['is_pending'] ?? false;
        }
    }

    public function render()
    {
        return view('livewire.savings-filter', [
            'transactions' => $this->filteredTransactions
        ]);
    }
}
