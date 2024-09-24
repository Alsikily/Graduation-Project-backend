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
        Schema::create('students_exams', function (Blueprint $table) {
            $table->id();
            $table->string("exam_degree", 10);
            $table->string("student_degree", 10);
            $table->string("status", 10);
            $table->string("for", 5);
            $table->unsignedBigInteger("exam_id");
            $table->foreign("exam_id")
            -> references("id")
            -> on("exams")
            -> onUpdate("cascade")
            -> onDelete("cascade");
            $table->unsignedBigInteger("student_id");
            $table->foreign("student_id")
            -> references("id")
            -> on("students")
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
        Schema::dropIfExists('students_exams');
    }
};
