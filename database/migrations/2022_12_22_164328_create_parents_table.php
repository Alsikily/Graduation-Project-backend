<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up() {
        Schema::create('parents', function (Blueprint $table) {
            $table->id();
            $table->string("name", 100);
            $table->string("email", 100);
            $table->string("password");
            $table->string("phone", 20) -> nullable();
            $table->string("photo") -> nullable();
            $table->text("address") -> nullable();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('parents');
    }

};
