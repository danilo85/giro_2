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
        Schema::create('portfolio_works', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description');
            $table->text('content')->nullable();
            $table->foreignId('portfolio_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('client_id')->nullable()->constrained('clientes')->onDelete('set null');
            $table->foreignId('orcamento_id')->nullable()->constrained('orcamentos')->onDelete('set null');
            $table->date('project_date');
            $table->string('project_url')->nullable();
            $table->json('technologies')->nullable(); // Array de tecnologias
            $table->string('featured_image')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(false);
            $table->string('seo_title')->nullable();
            $table->text('seo_description')->nullable();
            $table->json('seo_keywords')->nullable();
            $table->integer('views_count')->default(0);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('portfolio_works');
    }
};
