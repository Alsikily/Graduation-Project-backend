<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Models
use App\Models\Exam;

class StudentsExams extends Model {

    use HasFactory;

    protected $guarded = [];

    public function exam() {

        return $this -> belongsTo(Exam::class, "exam_id", "id");

    }
    
    public function getStudentDegreeAttribute($value) {
        
        return round($value, 1);

    }
    
    public function getExamDegreeAttribute($value) {
        
        return round($value, 1);

    }

}
