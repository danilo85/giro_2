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
        Schema::create('credit_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nome_cartao');
            $table->string('bandeira', 100);
            $table->string('bandeira_logo_url', 500)->nullable();
            $table->decimal('limite_total', 15, 2);
            $table->decimal('limite_utilizado', 15, 2)->default(0.00);
            $table->integer('data_fechamento')->check('data_fechamento BETWEEN 1 AND 31');
            $table->integer('data_vencimento')->check('data_vencimento BETWEEN 1 AND 31');
            $table->boolean('ativo')->default(true);
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('data_vencimento');
            $table->index('ativo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('credit_cards');
    }
};
