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
        Schema::dropIfExists('portfolio_work_images');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Não vamos recriar a tabela antiga, pois ela foi substituída por portfolio_works_images
        // Se necessário, use a migration original: 2025_09_10_151815_create_portfolio_work_images_table
    }
};
