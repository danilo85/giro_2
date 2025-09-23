<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify the type enum column to include 'project_start'
        DB::statement("ALTER TABLE historico_entries MODIFY COLUMN type ENUM('manual', 'system', 'status_change', 'payment', 'project_start') NOT NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert the type enum column to original values
        DB::statement("ALTER TABLE historico_entries MODIFY COLUMN type ENUM('manual', 'system', 'status_change', 'payment') NOT NULL");
    }
};
