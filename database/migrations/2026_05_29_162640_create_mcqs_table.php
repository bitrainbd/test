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
        Schema::create('mcqs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('klass_id');
            $table->foreignId('subject_id');
            $table->enum('version',['BV','EV'])->default('BV');
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('duration')->comment('in minutes');
            $table->string('thumbnail')->nullable();
            $table->string('foreground_color')->nullable();
            $table->string('background_color')->nullable();
            $table->string('image')->nullable();
            $table->integer('total_marks');
            $table->integer('pass_marks');
            $table->enum('status',['ACTIVE','INACTIVE'])->default('ACTIVE');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mcqs');
    }
};
