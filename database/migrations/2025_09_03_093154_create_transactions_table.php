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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('bank_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('credit_card_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('category_id')->constrained()->onDelete('restrict');
            $table->string('descricao', 500);
            $table->decimal('valor', 15, 2);
            $table->enum('tipo', ['receita', 'despesa']);
            $table->date('data');
            $table->enum('status', ['pendente', 'pago', 'cancelado'])->default('pendente');
            $table->enum('frequency_type', ['unica', 'parcelada', 'recorrente'])->default('unica');
            $table->string('installment_id', 50)->nullable();
            $table->integer('installment_count')->nullable();
            $table->integer('installment_number')->nullable();
            $table->date('data_pagamento')->nullable();
            $table->text('observacoes')->nullable();
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('data');
            $table->index('status');
            $table->index('tipo');
            $table->index('installment_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
