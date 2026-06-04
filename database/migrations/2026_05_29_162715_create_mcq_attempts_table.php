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
        Schema::create('mcq_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mcq_stat_id')->constrained('mcq_stats')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('mcq_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('question_no');
            $table->unsignedTinyInteger('answer_no');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcq_attempts');
    }
};
