<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StudentFactory extends Factory {

    public function definition() {
        return [
            'name' => $this -> faker -> name,
            'email' => $this -> faker -> unique()->safeEmail(),
            'password' => '$2y$10$IcDNfFjZ29mCp30OyO7Uz.blaFWlWyWGEMLCkc2OA6KrsvTeGcHUq',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
