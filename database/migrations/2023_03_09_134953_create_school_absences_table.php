<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up() {
        Schema::create('school_absences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("teacher_id");
            $table->foreign("teacher_id")
            -> references("id")
            -> on("teachers")
            -> onUpdate("cascade")
            -> onDelete("cascade");
            $table->unsignedBigInteger("take_id");
            $table->foreign("take_id")
            -> references("id")
            -> on("school_t_absences")
            -> onUpdate("cascade")
            -> onDelete("cascade");
            $table->string("status", 10);
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('school_absences');
    }

};
