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
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string("name", 50);
            $table->unsignedBigInteger("school_id");
            $table->foreign("school_id")
            -> references("id")
            -> on("schools")
            -> onUpdate("cascade")
            -> onDelete("cascade");
            $table->unsignedBigInteger("class_id");
            $table->foreign("class_id")
            -> references("id")
            -> on("classes")
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
        Schema::dropIfExists('rooms');
    }
};
