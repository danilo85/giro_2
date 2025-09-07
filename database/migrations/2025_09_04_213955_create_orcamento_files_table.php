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
        Schema::create('orcamento_files', function (Blueprint $table) {
            $table->id();
            $table->foreignId('orcamento_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('nome_arquivo');
            $table->string('url_arquivo', 1000);
            $table->string('tipo_arquivo', 50);
            $table->unsignedBigInteger('tamanho');
            $table->enum('categoria', ['anexo', 'avatar', 'logo'])->default('anexo');
            $table->text('descricao')->nullable();
            $table->timestamps();
            
            $table->index(['orcamento_id', 'categoria']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orcamento_files');
    }
};