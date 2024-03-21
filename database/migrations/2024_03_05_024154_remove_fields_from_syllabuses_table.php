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
        Schema::table('syllabuses', function (Blueprint $table) {
            $table->dropForeign(['position_id']);
            $table->dropForeign(['group_id']);
            $table->dropForeign(['level_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('syllabuses', function (Blueprint $table) {
            //
        });
    }
};
