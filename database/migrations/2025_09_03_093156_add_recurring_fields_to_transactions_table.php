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
        Schema::table('transactions', function (Blueprint $table) {
            $table->boolean('is_recurring')->default(false)->after('frequency_type');
            $table->enum('recurring_type', ['monthly', 'weekly', 'yearly'])->nullable()->after('is_recurring');
            $table->date('recurring_end_date')->nullable()->after('recurring_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['is_recurring', 'recurring_type', 'recurring_end_date']);
        });
    }
};