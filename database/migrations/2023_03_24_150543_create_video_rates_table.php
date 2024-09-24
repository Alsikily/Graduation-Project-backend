<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up() {
        Schema::create('video_rates', function (Blueprint $table) {
            $table->tinyInteger("rate");
            $table->unsignedBigInteger("video_id");
            $table->foreign("video_id")
            -> references("id")
            -> on("videos")
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

    public function down() {
        Schema::dropIfExists('video_rates');
    }

};
