<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('server_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('server_id')->constrained()->cascadeOnDelete();
            $table->decimal('cpu_usage', 5, 2);
            $table->unsignedBigInteger('ram_used');
            $table->unsignedBigInteger('ram_total');
            $table->unsignedBigInteger('disk_used');
            $table->unsignedBigInteger('disk_total');
            $table->unsignedBigInteger('uptime_seconds');
            $table->timestamp('recorded_at');

            $table->index(['server_id', 'recorded_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('server_metrics');
    }
};
