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
        Schema::create('savings_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->enum('transaction_type', ['setor', 'tarik']);
            $table->enum('savings_type', ['pokok', 'wajib', 'sukarela']);
            $table->decimal('amount', 15, 2);
            $table->text('description')->nullable();
            $table->string('transaction_proof_url')->nullable();
            $table->timestamp('transaction_date');
            $table->foreignId('processed_by_admin_id')->nullable()->constrained('users');
            $table->timestamps();

            // Composite index for performance (very important for user transaction queries)
            $table->index(['user_id', 'transaction_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('savings_transactions');
    }
};
