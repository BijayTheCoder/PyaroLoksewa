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
        Schema::create('syllabus_parts', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('papers_id')->nullable();
            $table->foreign('papers_id')->references('id')->on('papers')->onDelete('cascade');
            $table->text('title')->nullable();
            $table->float('marks')->nullable();
            $table->float('percentage')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syllabus_parts');
    }
};
