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
    public function up()
    {
        Schema::create('exam_rooms', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("exam_id");
            $table->foreign("exam_id")
            -> references("id")
            -> on("exams")
            -> onUpdate("cascade")
            -> onDelete("cascade");
            $table->unsignedBigInteger("room_id");
            $table->foreign("room_id")
            -> references("id")
            -> on("rooms")
            -> onUpdate("cascade")
            -> onDelete("cascade");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('exam_rooms');
    }
};
