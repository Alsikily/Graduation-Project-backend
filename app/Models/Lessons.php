<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Teacher;
use App\Models\Subject;
use App\Models\Rooms;

class Lessons extends Model {
    use HasFactory;

    protected $guarded = [];

    public function Teacher() {

        return $this -> belongsTo(Teacher::class, 'teacher_id', 'id');

    }

    public function Subject() {

        return $this -> belongsTo(Subject::class, 'subject_id', 'id');

    }

    public function room() {

        return $this -> belongsTo(Rooms::class, 'room_id', 'id');

    }

}
