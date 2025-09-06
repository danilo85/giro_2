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
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nome');
            $table->enum('tipo', ['receita', 'despesa']);
            $table->string('icone_url', 500)->nullable();
            $table->string('cor', 7)->default('#6B7280');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('tipo');
            $table->index('ativo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
