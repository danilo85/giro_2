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
        Schema::create('temp_file_extensions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained('files')->onDelete('cascade');
            $table->timestamp('old_expires_at');
            $table->timestamp('new_expires_at');
            $table->text('reason')->nullable();
            $table->foreignId('extended_by')->constrained('users');
            $table->timestamp('created_at')->useCurrent();
            
            // Add indexes for performance
            $table->index('file_id', 'idx_temp_file_extensions_file_id');
            $table->index('created_at', 'idx_temp_file_extensions_created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_file_extensions');
    }
};
