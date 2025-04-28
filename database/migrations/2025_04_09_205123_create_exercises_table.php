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
        Schema::create('exercises', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->date('scheduled_for');
            $table->string('video_path');
            $table->integer('duration');
            $table->string('equipments');
            $table->string('instructions');
            $table->string('trainer_notes');
            $table->string('working_muscles')->nullable();
            $table->string('supporting_muscles')->nullable();
            $table->string('level');
           
            $table->boolean('saved')->default(false);
            $table->string("thumbnail");

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
