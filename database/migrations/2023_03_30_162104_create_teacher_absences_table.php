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
        Schema::create('teacher_absences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("student_id");
            $table->foreign("student_id")
            -> references("id")
            -> on("students")
            -> onUpdate("cascade")
            -> onDelete("cascade");
            $table->unsignedBigInteger("take_id");
            $table->foreign("take_id")
            -> references("id")
            -> on("teacher_t_absences")
            -> onUpdate("cascade")
            -> onDelete("cascade");
            $table->unsignedBigInteger("room_id");
            $table->foreign("room_id")
            -> references("id")
            -> on("rooms")
            -> onUpdate("cascade")
            -> onDelete("cascade");
            $table->string("status", 10);
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
        Schema::dropIfExists('teacher_absences');
    }
};
