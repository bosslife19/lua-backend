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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->unique();
            $table->timestamp('date_of_birth')->nullable();
            $table->string('gender')->nullable();
            $table->string('fitness_level')->nullable();
            $table->string('fitness_goal')->nullable();
            $table->string('cycle_behaviour')->nullable();
            $table->timestamp('menstrual_start')->nullable();
            $table->string('legal_name')->nullable();
            $table->string('preferred_name')->nullable();
            $table->integer('otp_code')->nullable();
            $table->timestamp('otp_expires_at')->nullable();

            $table->string('intentions')->nullable();
            $table->string('period_type')->nullable();
            $table->string('birth_control')->nullable();
            $table->string('cycle_regularity')->nullable();
            $table->string('flow_type')->nullable();
            $table->string('menstrual_symptoms')->nullable();
            $table->string('exercise_experience')->nullable();
            $table->string('exercise_frequency')->nullable();
            $table->string('daily_activity_level')->nullable();
            $table->string('additional_info')->nullable();
            $table->string('movement_space')->nullable();
            $table->string('health_conditions')->nullable();
            $table->string('wellness_support_methods')->nullable();
            $table->string('movement_considerations')->nullable();
            $table->string('recent_surgical_procedures')->nullable();
            $table->string('pregnancy')->nullable();
            $table->string('movement_response')->nullable();
            $table->string('healthcare_provider')->nullable();
            $table->string('additional_health_info')->nullable();
           
            $table->boolean('heart_condition_or_hbp')->nullable();
            $table->boolean('chest_pain')->nullable();
            $table->boolean('lost_consciousness')->nullable();
            $table->boolean('other_chronic_condition')->nullable();
            $table->boolean('medication_for_chronic_condition')->nullable();
            $table->boolean('bone_or_ligament_problem')->nullable();
            $table->boolean('medically_supervised_activity')->nullable();

            $table->string('pronouns')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('sessions');
    }
};
