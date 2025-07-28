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
        Schema::table('loans', function (Blueprint $table) {
            $table->text('approval_notes')->nullable()->after('approved_by_admin_id');
            $table->timestamp('first_installment_date')->nullable()->after('approval_notes');
            $table->decimal('total_paid', 15, 2)->default(0)->after('first_installment_date');
            $table->decimal('remaining_balance', 15, 2)->default(0)->after('total_paid');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            $table->dropColumn(['approval_notes', 'first_installment_date', 'total_paid', 'remaining_balance']);
        });
    }
};
