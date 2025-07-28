<?php

// Example migration - only create if business requirements demand it
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('loan_installments', function (Blueprint $table) {
            // Only add if truly needed for business requirements
            $table->enum('payment_method', ['cash', 'transfer'])->nullable()->after('amount');
            $table->string('account_number', 50)->nullable()->after('payment_method');
            $table->string('account_holder_name')->nullable()->after('account_number');
            $table->string('bank_name', 100)->nullable()->after('account_holder_name');

            // Index for reporting if needed
            $table->index('payment_method');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loan_installments', function (Blueprint $table) {
            $table->dropIndex(['payment_method']);
            $table->dropColumn([
                'payment_method',
                'account_number',
                'account_holder_name',
                'bank_name'
            ]);
        });
    }
};
