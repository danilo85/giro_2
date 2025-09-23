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
        Schema::table('social_posts', function (Blueprint $table) {
            $table->date('scheduled_date')->nullable()->after('status');
            $table->time('scheduled_time')->nullable()->after('scheduled_date');
            
            $table->index('scheduled_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('social_posts', function (Blueprint $table) {
            $table->dropIndex(['scheduled_date']);
            $table->dropColumn(['scheduled_date', 'scheduled_time']);
        });
    }
};
