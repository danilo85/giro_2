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
        Schema::create('temp_file_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('file_id')->constrained('files')->onDelete('cascade');
            $table->enum('notification_type', ['24h_warning', '1h_warning', 'expired']);
            $table->timestamp('sent_at')->useCurrent();
            $table->boolean('is_read')->default(false);
            $table->timestamp('created_at')->useCurrent();
            
            // Add indexes for performance
            $table->index('file_id', 'idx_temp_file_notifications_file_id');
            $table->index('notification_type', 'idx_temp_file_notifications_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_file_notifications');
    }
};
