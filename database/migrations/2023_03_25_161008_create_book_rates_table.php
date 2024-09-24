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
        Schema::create('book_rates', function (Blueprint $table) {
            $table->tinyInteger("rate");
            $table->unsignedBigInteger("book_id");
            $table->foreign("book_id")
            -> references("id")
            -> on("books")
            -> onUpdate("cascade")
            -> onDelete("cascade");
            $table->unsignedBigInteger("student_id");
            $table->foreign("student_id")
            -> references("id")
            -> on("students")
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
        Schema::dropIfExists('book_rates');
    }
};
