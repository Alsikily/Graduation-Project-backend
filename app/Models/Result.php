<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Models
use App\Models\Rooms;
use App\Models\Student;
use App\Models\ResultStudent;

class Result extends Model {

    use HasFactory;

    protected $guarded = [];

    public function room() {

        return $this -> belongsTo(Rooms::class, "room_id", "id");

    }

    public function students() {

        return $this -> hasMany(Student::class, "student_id", "id");

    }

    public function results() {

        return $this -> hasMany(ResultStudent::class, "result_id", "id")
                        -> orderBy("student_id")
                        -> orderBy("subject_id");

    }

}
