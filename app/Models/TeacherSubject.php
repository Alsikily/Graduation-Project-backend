<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Models
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Lessons;

class TeacherSubject extends Model {
    use HasFactory;

    protected $guarded = [];

    public function subject() {

        return $this -> belongsTo(Subject::class, "subject_id", "id");

    }

    public function teacher() {

        return $this -> belongsTo(Teacher::class, "teacher_id", "id");

    }

    public function lessons() {

        return $this -> hasMany(Lessons::class, "teacher_id", "teacher_id");

    }

}
