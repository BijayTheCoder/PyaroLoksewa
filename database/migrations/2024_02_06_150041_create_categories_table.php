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
        Schema::create('categories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('parent_id')->nullable();
            $table->text('title');
            $table->text('short_title')->nullable();
            $table->text('slug')->nullable();
            $table->longText('description')->nullable();
            $table->string('image');
            $table->integer('order')->nullable();
            $table->boolean('publish')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
