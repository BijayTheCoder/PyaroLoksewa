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
        Schema::create('syllabuses', function (Blueprint $table) {
            $table->increments('integer');
            $table->text('title');
            $table->longText('description')->nullable();
            $table->unsignedInteger('position_id')->nullable();
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade'); 
            $table->string('image')->nullable();
            $table->boolean('publish')->nullable();
            $table->integer('order')->nullable();           
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('syllabuses');
    }
};
