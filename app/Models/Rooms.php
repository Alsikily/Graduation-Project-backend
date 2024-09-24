<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Models
use App\Models\Classes;
use App\Models\Periods;
use App\Models\Days;
use App\Models\Lessons;
use App\Models\Subject;
use App\Models\Student;
use App\Models\ResultStudent;
use App\Models\TeacherTAbsence;
use App\Models\TeacherAbsences;

class Rooms extends Model {
    use HasFactory;

    protected $guarded = [];

    public function mainClass() {

        return $this -> belongsTo(Classes::class, 'class_id', 'id');

    }

    public function Periods() {

        return $this -> hasMany(Periods::class, 'room_id', 'id');

    }

    public function students() {

        return $this -> hasMany(Student::class, "room_id", "id");

    }

    public function ResultStudents() {

        return $this -> hasMany(ResultStudent::class, "room_id", "id");

    }

    public function subjects() {

        return $this -> belongsToMany(Subject::class, "lessons", "room_id", "subject_id")
                    -> groupBy("lessons.room_id", "lessons.subject_id");

    }

    public function absence() {

        return $this -> hasMany(TeacherTAbsence::class, "room_id", "id");

    }

    public function absences() {

        return $this -> hasMany(TeacherAbsences::class, "room_id", "id");

    }

}
