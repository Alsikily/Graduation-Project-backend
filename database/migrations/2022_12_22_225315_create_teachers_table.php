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
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string("name", 100);
            $table->string("email", 100);
            $table->string("password");
            $table->string("phone", 20) -> nullable();
            $table->string("photo") -> nullable();
            $table->text("address") -> nullable();
            $table->integer("rank") -> default(0);
            $table->unsignedBigInteger("school_id") -> nullable();
            $table->foreign("school_id")
            -> references("id")
            -> on("schools")
            -> onUpdate("cascade")
            -> onDelete("cascade");
            $table->unsignedBigInteger("subject_id") -> nullable();
            $table->foreign("subject_id")
            -> references("id")
            -> on("subjects")
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
        Schema::dropIfExists('teachers');
    }
};
