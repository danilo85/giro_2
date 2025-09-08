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
        Schema::table('modelos_propostas', function (Blueprint $table) {
            $table->string('categoria', 100)->nullable()->after('conteudo');
            $table->string('status', 50)->default('ativo')->after('categoria');
            $table->text('descricao')->nullable()->after('status');
            $table->text('observacoes')->nullable()->after('descricao');
            $table->decimal('valor_padrao', 10, 2)->nullable()->after('observacoes');
            $table->integer('prazo_padrao')->nullable()->comment('Prazo em dias')->after('valor_padrao');
            $table->json('autores_padrao')->nullable()->after('prazo_padrao');
            
            $table->index('categoria');
            $table->index('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modelos_propostas', function (Blueprint $table) {
            $table->dropIndex(['categoria']);
            $table->dropIndex(['status']);
            $table->dropColumn([
                'categoria',
                'status', 
                'descricao',
                'observacoes',
                'valor_padrao',
                'prazo_padrao',
                'autores_padrao'
            ]);
        });
    }
};
