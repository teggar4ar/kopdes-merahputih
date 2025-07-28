<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\SystemSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoanController extends Controller
{
    /**
     * Display the loan management page
     */
    public function index()
    {
        $user = Auth::user();

        // Get user's loans
        $loans = $user->loans()
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Get active loan (if any)
        $activeLoan = $user->loans()
            ->whereIn('status', ['approved', 'disbursed'])
            ->first();

        // Get loan interest rate from system settings
        $interestRate = SystemSetting::where('setting_key', 'loan_interest_rate')
            ->value('setting_value') ?? 12; // Default 12% if not set

        return view('loans.index', compact('loans', 'activeLoan', 'interestRate'));
    }

    /**
     * Show loan application status
     */
    public function show(Loan $loan)
    {
        // Ensure user can only view their own loans
        if ($loan->user_id !== Auth::id()) {
            abort(403);
        }

        return view('loans.show', compact('loan'));
    }
}
