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
            $table->string('fname', 255);
            $table->string('lname', 255);
            $table->string('role', 255);
            $table->string('tel', 255);
            $table->string('profession')->nullable();
            $table->string('email')->unique();
            $table->string('job_title')->nullable();
            $table->string('motivation')->nullable();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('status')->default('pending');
            $table->string('subscription');
            $table->string('password');
            $table->string('avatar')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('lang')->default('fr');
            $table->timestamp('free_trial_start_at')->nullable();
            $table->timestamp('free_trial_end_at')->nullable();
            $table->string('payement_plan_id')->nullable();
            $table->string('payement_method')->nullable()->default('self');
            $table->string('payement_status')->nullable()->default('pending');
            $table->string('payement_group_id')->nullable();
            $table->timestamp('payement_end_at')->nullable();
            $table->timestamp('last_login')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
