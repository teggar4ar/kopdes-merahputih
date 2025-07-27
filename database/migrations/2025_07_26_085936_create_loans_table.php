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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->decimal('principal_amount', 15, 2);
            $table->float('interest_rate'); // Locked interest rate at application time
            $table->integer('duration_months');
            $table->enum('status', ['pending', 'approved', 'rejected', 'completed', 'disbursed'])
                ->default('pending');
            $table->text('reason')->nullable();
            $table->timestamp('application_date');
            $table->timestamp('approval_date')->nullable();
            $table->foreignId('approved_by_admin_id')->nullable()->constrained('users');
            $table->timestamps();
            $table->softDeletes();

            // Indexes for performance
            $table->index('user_id');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
