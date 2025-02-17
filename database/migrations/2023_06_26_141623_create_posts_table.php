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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('type');
            $table->string('subject');
            $table->string('files')->nullable()->default(null);
            $table->string('status',20)->nullable()->default(null);
            $table->text('references')->nullable()->default(null);
            $table->text('_tags')->nullable()->default(null);
            $table->string('visibility')->nullable()->default('private');
            $table->string('lang')->default('fr');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
