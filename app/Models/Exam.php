<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Models
use App\Models\Questions;
use App\Models\StudentsExams;
use App\Models\Subject;

class Exam extends Model {

    use HasFactory;
    
    protected $guarded = [];

    public function teacher() {

        return $this -> belongsTo(Teacher::class, "teacher_id", "id");

    }

    public function questions() {

        return $this -> hasMany(Questions::class, "exam_id", "id");

    }

    public function StudentExam() {

        return $this -> hasOne(StudentsExams::class, "exam_id", "id");

    }

    public function subject() {

        return $this -> belongsTo(Subject::class, "subject_id", "id");

    }

}
