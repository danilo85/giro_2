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
        Schema::create('orcamentos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cliente_id')->constrained('clientes')->onDelete('cascade');
            $table->string('titulo');
            $table->text('descricao')->nullable();
            $table->decimal('valor_total', 10, 2);
            $table->integer('prazo_dias');
            $table->date('data_orcamento');
            $table->enum('status', ['rascunho', 'analisando', 'rejeitado', 'aprovado', 'pago', 'finalizado'])->default('rascunho');
            $table->string('token_publico', 64)->unique();
            $table->timestamps();
            
            $table->index('cliente_id');
            $table->index('status');
            $table->index('data_orcamento');
            $table->index('token_publico');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orcamentos');
    }
};
