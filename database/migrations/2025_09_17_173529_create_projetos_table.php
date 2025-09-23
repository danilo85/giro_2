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
        Schema::create('projetos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orcamento_id');
            $table->unsignedBigInteger('etapa_id');
            $table->integer('posicao')->default(0);
            $table->timestamp('moved_at')->nullable();
            $table->timestamps();
            
            // Foreign keys
            $table->foreign('orcamento_id')->references('id')->on('orcamentos')->onDelete('cascade');
            $table->foreign('etapa_id')->references('id')->on('etapas_kanban')->onDelete('restrict');
            
            // Índices
            $table->index('orcamento_id');
            $table->index('etapa_id');
            $table->index(['etapa_id', 'posicao']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projetos');
    }
};
