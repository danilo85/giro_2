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
        Schema::table('files', function (Blueprint $table) {
            $table->timestamp('expires_at')->nullable()->after('is_temporary');
            
            // Add indexes for performance
            $table->index('expires_at', 'idx_files_expires_at');
            $table->index(['is_temporary', 'expires_at'], 'idx_files_is_temporary_expires_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('files', function (Blueprint $table) {
            $table->dropIndex('idx_files_is_temporary_expires_at');
            $table->dropIndex('idx_files_expires_at');
            $table->dropColumn('expires_at');
        });
    }
};
