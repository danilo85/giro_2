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
        // Primeiro, vamos alterar a coluna para VARCHAR temporariamente
        DB::statement("ALTER TABLE transactions MODIFY COLUMN frequency_type VARCHAR(20) DEFAULT 'unica'");
        
        // Agora vamos alterar para o novo ENUM com os valores corretos
        DB::statement("ALTER TABLE transactions MODIFY COLUMN frequency_type ENUM('unica', 'parcelada', 'recorrente', 'mensal', 'semanal', 'anual', 'diaria') DEFAULT 'unica'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverter para o ENUM original
        DB::statement("ALTER TABLE transactions MODIFY COLUMN frequency_type ENUM('unica', 'parcelada', 'recorrente') DEFAULT 'unica'");
    }
};