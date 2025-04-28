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
        Schema::table('exercises', function (Blueprint $table) {
            $table->json('videos')->nullable()->after('scheduled_for');
            $table->dropColumn('video_path');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
        Schema::table('exercises', function (Blueprint $table) {
            $table->string('video_path')->nullable();
            $table->dropColumn('videos');
        });
    }
};
