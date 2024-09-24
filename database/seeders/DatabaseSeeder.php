<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

// Models
use App\Models\Student;
use App\Models\Teacher;
use App\Models\School;
use App\Models\Parents;
use App\Models\Days;

use App\Models\ConstClasses;
use App\Models\ConstSubject;

class DatabaseSeeder extends Seeder {
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run() {

        $constClasses = [
            "First Primary",
            "Second Primary",
            "Third Primary",
            "Fourth Primary",
            "Fifth Primary",
            "Sixth Primary",
            "First Preparatory",
            "Second Preparatory",
            "Third Preparatory",
            "First Secondary",
            "Second Secondary",
            "Third Secondary",
        ];

        $constSubjects = [
            "Physics",
            "Math",
            "Static",
            "Dynamic",
            "Algebra",
            "Calculus",
            "Trigonometry",
            "Chemistry",
            "Science",
            "History",
            "Geography",
            "Philosophy",
            "Psychology",
            "Social studies",
            "Biology",
            "Geology",
            "Religion",
            "Art",
            "Computer",
            "Arabic",
            "English",
            "French",
            "German",
            "Italian",
        ];

        $days = [
            'Saturday',
            'Sunday',
            'Monday',
            'Tuesday',
            'Wednesday',
            'Thursday',
        ];

        foreach ($constClasses as $class) {

            ConstClasses::create([
                "name" => $class
            ]);

        }
        
        foreach ($constSubjects as $subject) {

            ConstSubject::create([
                "name" => $subject
            ]);

        }

        foreach ($days as $day) {

            Days::create([
                "day" => $day
            ]);

        }

        // \App\Models\User::factory(10)->create();

        // \App\Models\ConstClasses::factory()->create([
        //     'name' => 'Test User',
        // ]);

    }
}
