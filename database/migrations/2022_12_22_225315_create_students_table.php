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
        Schema::create('students', function (Blueprint $table) {
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
            $table->unsignedBigInteger("room_id") -> nullable();
            $table->foreign("room_id")
            -> references("id")
            -> on("rooms")
            -> onUpdate("cascade")
            -> onDelete("set null");
            $table->unsignedBigInteger("parent_id") -> nullable();
            $table->foreign("parent_id")
            -> references("id")
            -> on("parents")
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
        Schema::dropIfExists('students');
    }
};
