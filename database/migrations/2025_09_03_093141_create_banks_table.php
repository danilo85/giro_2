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
        Schema::create('banks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nome');
            $table->string('banco');
            $table->string('logo_url', 500)->nullable();
            $table->decimal('saldo_inicial', 15, 2)->default(0.00);
            $table->decimal('saldo_atual', 15, 2)->default(0.00);
            $table->string('numero_conta', 50)->nullable();
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('ativo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('banks');
    }
};
