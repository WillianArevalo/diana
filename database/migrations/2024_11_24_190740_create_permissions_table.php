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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->enum("type", ["Permiso con", "Permison sin", "Incapacidad", "Otros"]);
            $table->datetime("date_start");
            $table->datetime("date_end");
            $table->text("description")->nullable();
            $table->string("file")->nullable();
            $table->foreignId("user_id")->constrained("users");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};