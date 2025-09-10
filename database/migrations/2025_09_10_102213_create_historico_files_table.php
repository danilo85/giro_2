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
        Schema::create('historico_files', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('historico_entry_id');
            $table->string('original_name');
            $table->string('file_path', 500);
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('file_size');
            $table->timestamp('created_at')->useCurrent();
            
            $table->foreign('historico_entry_id')->references('id')->on('historico_entries')->onDelete('cascade');
            
            $table->index('historico_entry_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historico_files');
    }
};
