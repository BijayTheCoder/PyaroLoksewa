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
        Schema::create('syllabus_steps_pivot', function (Blueprint $table) {
            $table->unsignedInteger('syllabus_id')->nullable();
            $table->foreign('syllabus_id')->references('id')->on('syllabuses')->onDelete('cascade');
            $table->unsignedInteger('step_id')->nullable();
            $table->foreign('step_id')->references('id')->on('steps')->onDelete('cascade');  
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syllabus_steps_pivot');
    }
};
