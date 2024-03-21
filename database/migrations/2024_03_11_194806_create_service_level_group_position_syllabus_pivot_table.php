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
        Schema::create('service_level_group_position_syllabus_pivot', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('syllabus_id');
            $table->foreign('syllabus_id')->references('id')->on('syllabuses')->onDelete('cascade'); 
            $table->text('position_id')->nullable(); 
            $table->text('group_id')->nullable();
            $table->text('level_id')->nullable();
            $table->text('service_id')->nullable();
            $table->timestamps();
        }); 
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_level_group_position_syllabus_pivot');
    }
};
