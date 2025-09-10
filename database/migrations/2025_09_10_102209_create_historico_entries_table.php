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
        Schema::create('historico_entries', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('orcamento_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->enum('type', ['manual', 'system', 'status_change', 'payment'])->default('manual');
            $table->string('title');
            $table->text('description')->nullable();
            $table->dateTime('entry_date');
            $table->json('metadata')->nullable();
            $table->timestamps();
            
            $table->foreign('orcamento_id')->references('id')->on('orcamentos')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            
            $table->index('orcamento_id');
            $table->index(['entry_date', 'orcamento_id']);
            $table->index('type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historico_entries');
    }
};
