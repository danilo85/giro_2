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
        Schema::create('temp_file_settings', function (Blueprint $table) {
            $table->id();
            $table->integer('default_expiry_days')->default(7)->check('default_expiry_days > 0');
            $table->integer('max_expiry_days')->default(30);
            $table->boolean('auto_cleanup_enabled')->default(true);
            $table->time('cleanup_time')->default('02:00:00');
            $table->timestamps();
        });
        
        // Insert default settings
        DB::table('temp_file_settings')->insert([
            'default_expiry_days' => 7,
            'max_expiry_days' => 30,
            'auto_cleanup_enabled' => true,
            'cleanup_time' => '02:00:00',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('temp_file_settings');
    }
};
