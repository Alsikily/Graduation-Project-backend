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
        Schema::create('result_students', function (Blueprint $table) {
            $table->id();
            $table->string("subject_degree", 10) -> default(0);
            $table->string("student_degree", 10) -> default(0);
            $table->unsignedBigInteger("student_id");
            $table->foreign("student_id")
            -> references("id")
            -> on("students")
            -> onUpdate("cascade")
            -> onDelete("cascade");
            $table->unsignedBigInteger("result_id");
            $table->foreign("result_id")
            -> references("id")
            -> on("results")
            -> onUpdate("cascade")
            -> onDelete("cascade");
            $table->unsignedBigInteger("subject_id");
            $table->foreign("subject_id")
            -> references("id")
            -> on("subjects")
            -> onUpdate("cascade")
            -> onDelete("cascade");
            // $table->unsignedBigInteger("school_id");
            // $table->foreign("school_id")
            // -> references("id")
            // -> on("schools")
            // -> onUpdate("cascade")
            // -> onDelete("cascade");
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
        Schema::dropIfExists('result_students');
    }
};
