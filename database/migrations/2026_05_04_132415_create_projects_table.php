<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->enum('type', ['web', 'mobile_android', 'mobile_ios', 'mobile_both', 'desktop']);
            $table->string('thumbnail')->nullable();
            $table->json('screenshots')->nullable();
            $table->string('web_url')->nullable();
            $table->string('play_store_url')->nullable();
            $table->string('app_store_url')->nullable();
            $table->string('desktop_url')->nullable();
            $table->json('tech_stack')->nullable();
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
