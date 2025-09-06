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
        Schema::table('banks', function (Blueprint $table) {
            $table->string('agencia', 20)->nullable()->after('banco');
            $table->string('conta', 50)->nullable()->after('agencia');
            $table->string('tipo_conta', 50)->nullable()->after('conta');
            $table->text('observacoes')->nullable()->after('ativo');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('banks', function (Blueprint $table) {
            $table->dropColumn(['agencia', 'conta', 'tipo_conta', 'observacoes']);
        });
    }
};