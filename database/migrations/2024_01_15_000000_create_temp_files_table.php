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
        Schema::create('temp_files', function (Blueprint $table) {
            $table->id();
            $table->string('temp_id'); // ID temporário único
            $table->string('nome_arquivo');
            $table->text('url_arquivo');
            $table->string('tipo_arquivo');
            $table->bigInteger('tamanho');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
            
            $table->index('temp_id');
            $table->index('user_id');
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_files');
    }
};