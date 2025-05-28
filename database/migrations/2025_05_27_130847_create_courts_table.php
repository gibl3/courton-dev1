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
        Schema::create('courts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->enum('type', ['professional', 'standard', 'training']);
            $table->text('description')->nullable();
            $table->string('image_path')->nullable();
            $table->enum('status', ['available', 'unavailable', 'maintenance'])->default('available');
            $table->decimal('rate_per_hour', 8, 2);
            $table->decimal('weekend_rate_per_hour', 8, 2);
            $table->time('opening_time');
            $table->time('closing_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courts');
    }
};
