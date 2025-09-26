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
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('file_categories')->onDelete('set null');
            $table->string('original_name');
            $table->string('stored_name');
            $table->string('mime_type', 100);
            $table->unsignedBigInteger('file_size');
            $table->boolean('is_temporary')->default(false);
            $table->text('description')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('category_id');
            $table->index('is_temporary');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('files');
    }
};
