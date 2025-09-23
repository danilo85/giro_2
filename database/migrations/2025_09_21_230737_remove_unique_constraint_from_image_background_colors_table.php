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
        // Check if the constraint exists and remove it
        $indexes = DB::select("SHOW INDEX FROM image_background_colors WHERE Key_name LIKE '%unique%'");
        
        if (!empty($indexes)) {
            foreach ($indexes as $index) {
                if (strpos($index->Key_name, 'unique') !== false) {
                    DB::statement("ALTER TABLE image_background_colors DROP INDEX `{$index->Key_name}`");
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('image_background_colors', function (Blueprint $table) {
            // Re-add the unique constraint if needed
            $table->unique(['user_id', 'is_default'], 'unique_default_per_user');
        });
    }
};
