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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone_number')->after('email');
            $table->json('current_address')->after('email')->nullable();
            $table->json('permanent_address')->after('current_address')->nullable();
            $table->string('highest_level_qualification')->after('permanent_address')->nullable();
            $table->string('highest_level_qualification_faculty')->after('highest_level_qualification')->nullable();
            $table->timeStamp('phone_verified_at')->after('highest_level_qualification_faculty')->nullable();
        });
    }

    /**
     * Reverse the migrations.permanent_address
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone_number');
            $table->dropColumn('current_address');
            $table->dropColumn('permanent_address');
            $table->dropColumn('highest_level_qualification');
            $table->dropColumn('highest_level_qualification_faculty');
            $table->dropColumn('phone_verified_at');
        });
    }
};
