<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('etapas_kanban', function (Blueprint $table) {
            $table->id();
            $table->string('nome', 100);
            $table->string('cor', 7)->default('#6B7280');
            $table->integer('ordem')->default(0);
            $table->boolean('ativa')->default(true);
            $table->timestamps();
            
            // Índices
            $table->index('ordem');
            $table->index('ativa');
        });
        
        // Inserir dados iniciais
        DB::table('etapas_kanban')->insert([
            ['nome' => 'Aprovado', 'cor' => '#10B981', 'ordem' => 1, 'ativa' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Em Andamento', 'cor' => '#3B82F6', 'ordem' => 2, 'ativa' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Em Revisão', 'cor' => '#F59E0B', 'ordem' => 3, 'ativa' => true, 'created_at' => now(), 'updated_at' => now()],
            ['nome' => 'Concluído', 'cor' => '#6366F1', 'ordem' => 4, 'ativa' => true, 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('etapas_kanban');
    }
};
