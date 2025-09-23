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
        Schema::table('image_background_colors', function (Blueprint $table) {
            $table->unsignedBigInteger('post_id')->nullable()->after('user_id');
            $table->string('color_name')->nullable()->change();
            
            $table->foreign('post_id')->references('id')->on('social_posts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('image_background_colors', function (Blueprint $table) {
            $table->dropForeign(['post_id']);
            $table->dropColumn('post_id');
            $table->string('color_name')->nullable(false)->change();
        });
    }
};
