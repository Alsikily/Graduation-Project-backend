<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Models
use App\Models\Exam;

class ExamRooms extends Model {

    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function exam() {

        return $this -> belongsTo(Exam::class, "exam_id", "id");

    }

}
