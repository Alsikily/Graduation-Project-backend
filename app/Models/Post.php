<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

// Models
use App\Models\Comments;
use App\Models\Student;

class Post extends Model {

    use HasFactory;

    protected $guarded = [];

    public function student() {

        return $this -> belongsTo(Student::class, "student_id", "id");

    }

    public function comments() {

        return $this -> hasMany(Comments::class, "post_id", "id");

    }

    public function getCreatedAtAttribute($value) {

        $carbonDate = new Carbon($value);
        return $carbonDate -> diffForHumans();

    }

}
