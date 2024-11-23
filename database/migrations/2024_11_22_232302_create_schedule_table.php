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
        Schema::create('schedule', function (Blueprint $table) {
            $table->id();
            $table->enum("type", ["day", "night"]);
            $table->date("date_start");
            $table->date("date_end");
            $table->time("time_start");
            $table->time("time_end");
            $table->time("break_start");
            $table->time("break_end");
            $table->integer("hours_day");
            $table->integer("hours_night");
            $table->foreignId("workplace_id")->nullable()->constrained("workplace");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedule');
    }
};
