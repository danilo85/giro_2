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
        // Remove URLs problemáticos que estão sendo bloqueados por CORS
        // Deixar logo_url como null para usar emojis como fallback
        \DB::table('banks')->update(['logo_url' => null]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Não há necessidade de reverter, pois os URLs não funcionavam
    }
};
