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
        Schema::create('image_background_colors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('color_name', 100);
            $table->string('color_hex', 7); // #FFFFFF format
            $table->boolean('is_default')->default(false);
            $table->timestamps();
            
            // Ensure only one default color per user (only when is_default is true)
            // We'll handle this constraint in the application logic instead
            $table->index(['user_id', 'is_default']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('image_background_colors');
    }
};
