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
        Schema::create('workout_time_logs', function (Blueprint $table) {
            $table->id();
    $table->foreignId('user_id')->constrained()->onDelete('cascade');
    $table->date('date'); // YYYY-MM-DD
    $table->integer('seconds_watched')->default(0); // total time watched today
    $table->json('exercises_done')->nullable();
    $table->string('status')->nullable();
    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_time_logs');
    }
};
