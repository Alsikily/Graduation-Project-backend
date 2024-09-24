<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('teacher_t_absences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("room_id");
            $table->foreign("room_id")
            -> references("id")
            -> on("rooms")
            -> onUpdate("cascade")
            -> onDelete("cascade");
            $table->unsignedBigInteger("teacher_id");
            $table->foreign("teacher_id")
            -> references("id")
            -> on("teachers")
            -> onUpdate("cascade")
            -> onDelete("cascade");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('teacher_t_absences');
    }
};
