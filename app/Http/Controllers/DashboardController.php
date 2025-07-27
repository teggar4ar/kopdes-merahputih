<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Loan;
use App\Models\SavingsTransaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the member dashboard.
     */
    public function index(): View
    {
        $user = Auth::user();

        // Get savings summary data
        $savingsSummary = $this->getSavingsSummary($user);

        // Get loan summary data
        $loanSummary = $this->getLoanSummary($user);

        // Get latest announcements
        $announcements = $this->getLatestAnnouncements();

        return view('dashboard', compact('savingsSummary', 'loanSummary', 'announcements'));
    }

    /**
     * Calculate savings balances for each savings type.
     */
    private function getSavingsSummary($user)
    {
        // Calculate total savings by type with correct business logic (only completed transactions)

        // Simpanan Wajib (Mandatory): Monthly payments of Rp 50,000
        $wajibSavings = $user->savingsTransactions()
            ->completed()
            ->where('savings_type', 'wajib')
            ->where('transaction_type', 'setor')
            ->sum('amount');

        // Simpanan Pokok (Principal): One-time payment of Rp 100,000
        $pokokSavings = $user->savingsTransactions()
            ->completed()
            ->where('savings_type', 'pokok')
            ->where('transaction_type', 'setor')
            ->sum('amount');

        // Calculate net voluntary savings (deposits minus withdrawals, only completed)
        $sukarelaDeposits = $user->savingsTransactions()
            ->completed()
            ->where('savings_type', 'sukarela')
            ->where('transaction_type', 'setor')
            ->sum('amount');

        $sukarelaWithdrawals = $user->savingsTransactions()
            ->completed()
            ->where('savings_type', 'sukarela')
            ->where('transaction_type', 'tarik')
            ->sum('amount');

        $sukarelaBalance = $sukarelaDeposits - $sukarelaWithdrawals;

        $totalSavings = $wajibSavings + $pokokSavings + $sukarelaBalance;

        // Check principal savings status (one-time Rp 100,000)
        $hasPaidPrincipal = $user->hasPaidPrincipalSavings();

        // Check mandatory savings status (monthly Rp 50,000)
        $mandatoryPaymentsCount = $user->getMandatoryPaymentsCount();
        $isCurrentOnMandatory = $user->isCurrentOnMandatorySavings();

        // Check if current month's mandatory payment has been made
        $hasCurrentMonthMandatoryPayment = $user->hasCurrentMonthMandatoryPayment();

        // Calculate months since membership for better mandatory payment context
        $monthsSinceMembership = $user->created_at->diffInMonths(now());
        $expectedMandatoryPayments = max(1, $monthsSinceMembership);
        $missingMandatoryPayments = max(0, $expectedMandatoryPayments - $mandatoryPaymentsCount);

        return [
            'wajib_savings' => $wajibSavings,
            'pokok_savings' => $pokokSavings,
            'sukarela_savings' => $sukarelaBalance,
            'total_savings' => $totalSavings,
            'has_paid_principal' => $hasPaidPrincipal,
            'mandatory_payments_count' => $mandatoryPaymentsCount,
            'is_current_on_mandatory' => $isCurrentOnMandatory,
            'has_current_month_mandatory_payment' => $hasCurrentMonthMandatoryPayment,
            'months_since_membership' => $monthsSinceMembership,
            'expected_mandatory_payments' => $expectedMandatoryPayments,
            'missing_mandatory_payments' => $missingMandatoryPayments,
        ];
    }

    /**
     * Get loan summary for the user.
     */
    private function getLoanSummary($user): array
    {
        // Get active loan (approved or disbursed)
        $activeLoan = Loan::where('user_id', $user->id)
            ->whereIn('status', ['approved', 'disbursed'])
            ->first();

        if (!$activeLoan) {
            return [
                'has_active_loan' => false,
                'message' => 'Tidak ada pinjaman aktif'
            ];
        }

        // Calculate total paid installments
        $totalPaid = $activeLoan->loanInstallments()->sum('amount');

        // Calculate remaining balance
        $remainingBalance = $activeLoan->principal_amount - $totalPaid;

        // Calculate monthly payment (principal + interest divided by duration)
        $totalWithInterest = $activeLoan->principal_amount * (1 + ($activeLoan->interest_rate / 100));
        $monthlyPayment = $totalWithInterest / $activeLoan->duration_months;

        // Calculate next installment number
        $paidInstallments = $activeLoan->loanInstallments()->count();
        $nextInstallmentNumber = $paidInstallments + 1;

        return [
            'has_active_loan' => true,
            'loan' => $activeLoan,
            'principal_amount' => $activeLoan->principal_amount,
            'formatted_principal' => 'Rp ' . number_format($activeLoan->principal_amount, 0, ',', '.'),
            'total_paid' => $totalPaid,
            'formatted_total_paid' => 'Rp ' . number_format($totalPaid, 0, ',', '.'),
            'remaining_balance' => $remainingBalance,
            'formatted_remaining' => 'Rp ' . number_format($remainingBalance, 0, ',', '.'),
            'monthly_payment' => $monthlyPayment,
            'formatted_monthly_payment' => 'Rp ' . number_format($monthlyPayment, 0, ',', '.'),
            'duration_months' => $activeLoan->duration_months,
            'paid_installments' => $paidInstallments,
            'next_installment' => $nextInstallmentNumber,
            'interest_rate' => $activeLoan->interest_rate,
            'status' => $activeLoan->status
        ];
    }

    /**
     * Get the latest 3 published announcements.
     */
    private function getLatestAnnouncements()
    {
        return Announcement::published()
            ->latest('created_at')
            ->take(3)
            ->get();
    }

    /**
     * Get Indonesian label for savings type.
     */
    private function getSavingsTypeLabel(string $type): string
    {
        return match ($type) {
            'pokok' => 'Simpanan Pokok',
            'wajib' => 'Simpanan Wajib',
            'sukarela' => 'Simpanan Sukarela',
            default => ucfirst($type)
        };
    }
}
