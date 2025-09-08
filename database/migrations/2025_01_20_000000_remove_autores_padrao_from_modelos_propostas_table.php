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
            $table->dropColumn('autores_padrao');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('modelos_propostas', function (Blueprint $table) {
            $table->json('autores_padrao')->nullable()->after('prazo_padrao');
        });
    }
};