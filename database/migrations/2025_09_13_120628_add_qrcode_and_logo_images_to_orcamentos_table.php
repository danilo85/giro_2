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
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->string('qrcode_image')->nullable()->comment('Caminho da imagem do QR Code');
            $table->string('logo_image')->nullable()->comment('Caminho da imagem do logotipo/ropae');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orcamentos', function (Blueprint $table) {
            $table->dropColumn(['qrcode_image', 'logo_image']);
        });
    }
};
