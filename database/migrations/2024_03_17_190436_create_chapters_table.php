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
        Schema::create('chapters', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('syllabus_parts_id')->nullable();
            $table->foreign('syllabus_parts_id')->references('id')->on('syllabuses')->onDelete('cascade'); 
            $table->text('title')->nullable();
            $table->float('serial_no')->nullable();
            $table->float('marks')->nullable();
            $table->float('percentage')->nullable();
            $table->integer('parent_id')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chapters');
    }
};
