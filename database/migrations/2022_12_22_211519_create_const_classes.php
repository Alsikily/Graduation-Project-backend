<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up() {
        Schema::create('const_classes', function (Blueprint $table) {
            $table->tinyIncrements("id");
            $table->string("name", 50);
        });
    }

    public function down() {
        Schema::dropIfExists('const_classes');
    }

};
