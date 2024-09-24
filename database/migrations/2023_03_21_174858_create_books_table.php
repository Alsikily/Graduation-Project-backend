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
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string("name", 255);
            $table->text("description") -> nullable();
            $table->string("image", 255);
            $table->string("book", 255);
            $table->string("views", 10) -> default(0);
            $table->string("size", 10);
            $table->unsignedBigInteger("teacher_id");
            $table->foreign("teacher_id")
            -> references("id")
            -> on("teachers")
            -> onUpdate("cascade")
            -> onDelete("cascade");
            $table->unsignedBigInteger("subject_id");
            $table->foreign("subject_id")
            -> references("id")
            -> on("subjects")
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
        Schema::dropIfExists('books');
    }
};
