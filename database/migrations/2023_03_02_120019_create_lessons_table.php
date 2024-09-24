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
        Schema::create('lessons', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("teacher_id") -> nullable();
            $table->foreign("teacher_id")
            -> references("id")
            -> on("teachers")
            -> onUpdate("cascade")
            -> onDelete("cascade");
            $table->unsignedBigInteger("period_id");
            $table->foreign("period_id")
            -> references("id")
            -> on("periods")
            -> onUpdate("cascade")
            -> onDelete("cascade");
            $table->unsignedBigInteger("subject_id") -> nullable();
            $table->foreign("subject_id")
            -> references("id")
            -> on("subjects")
            -> onUpdate("cascade")
            -> onDelete("cascade");
            $table->unsignedBigInteger("day_id");
            $table->foreign("day_id")
            -> references("id")
            -> on("days")
            -> onUpdate("cascade")
            -> onDelete("cascade");
            $table->unsignedBigInteger("room_id");
            $table->foreign("room_id")
            -> references("id")
            -> on("rooms")
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
        Schema::dropIfExists('lessons');
    }
};
