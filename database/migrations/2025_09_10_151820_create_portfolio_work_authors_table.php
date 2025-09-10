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
        Schema::create('portfolio_work_authors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('portfolio_work_id')->constrained()->onDelete('cascade');
            $table->foreignId('author_id')->constrained('autores')->onDelete('cascade');
            $table->string('role')->nullable(); // Papel do autor no projeto
            $table->timestamps();
            
            // Evitar duplicatas
            $table->unique(['portfolio_work_id', 'author_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio_work_authors');
    }
};
