<?php

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
        Schema::table('savings_transactions', function (Blueprint $table) {
            $table->enum('status', ['pending', 'completed', 'rejected'])
                ->default('pending')
                ->after('processed_by_admin_id');

            // Add index for filtering by status
            $table->index(['status', 'transaction_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('savings_transactions', function (Blueprint $table) {
            $table->dropIndex(['status', 'transaction_type']);
            $table->dropColumn('status');
        });
    }
};
