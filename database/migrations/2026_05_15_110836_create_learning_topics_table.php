<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_topics', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('category', 16); // it, ai, language
            $table->string('status', 16);   // studied, current, planned
            $table->string('period', 7)->nullable(); // YYYY-MM
            $table->unsignedTinyInteger('progress')->default(0);
            $table->text('notes')->nullable();
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['status', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_topics');
    }
};
