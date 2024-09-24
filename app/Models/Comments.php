<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\carbon;

// Models
use App\Models\Student;

class Comments extends Model {

    use HasFactory;

    protected $guarded = [];

    public function student() {

        return $this -> belongsTo(Student::class, "student_id", "id");

    }

    public function getCreatedAtAttribute($value) {

        $carbonDate = new Carbon($value);
        return $carbonDate -> diffForHumans();

    }

}
