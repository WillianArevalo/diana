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
        Schema::create('marking', function (Blueprint $table) {
            $table->id();
            $table->date("date");
            $table->foreignId("user_id")->constrained("users");
            $table->time("entry_time")->nullable();
            $table->time("exit_time")->nullable();
            $table->time("lunch_time_start")->nullable();
            $table->time("lunch_time_end")->nullable();
            $table->enum("type", ["labor", "holiday", 'seventh_day'])->default("labor");
            $table->string("photo")->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('marking');
    }
};
