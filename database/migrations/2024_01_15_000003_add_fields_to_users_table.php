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
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('email');
            $table->boolean('is_admin')->default(false)->after('avatar');
            $table->boolean('is_active')->default(true)->after('is_admin');
            $table->timestamp('last_login_at')->nullable()->after('is_active');
            $table->string('last_login_ip')->nullable()->after('last_login_at');
            $table->boolean('is_online')->default(false)->after('last_login_ip');
            $table->timestamp('last_activity_at')->nullable()->after('is_online');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'avatar',
                'is_admin',
                'is_active',
                'last_login_at',
                'last_login_ip',
                'is_online',
                'last_activity_at'
            ]);
        });
    }
};