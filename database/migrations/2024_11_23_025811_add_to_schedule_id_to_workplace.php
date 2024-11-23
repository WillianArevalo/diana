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
        Schema::table('workplace', function (Blueprint $table) {
            $table->foreignId('daytime_schedule_id')->nullable()->constrained('schedule');
            $table->foreignId('nighttime_schedule_id')->nullable()->constrained('schedule');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workplace', function (Blueprint $table) {
            $table->dropForeign(['daytime_schedule_id']);
            $table->dropForeign(['nighttime_schedule_id']);
            $table->dropColumn('daytime_schedule_id');
            $table->dropColumn('nighttime_schedule_id');
        });
    }
};
