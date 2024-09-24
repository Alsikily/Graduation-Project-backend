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
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->text("body");
            $table->integer("likes") -> default(0);
            $table->integer("dislikes") -> default(0);
            $table->unsignedBigInteger("post_id");
            $table->foreign("post_id")
            -> references("id")
            -> on("posts")
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
        Schema::dropIfExists('comments');
    }
};
