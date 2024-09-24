<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Models
use App\Models\Course;
use App\Models\Video;
use App\Models\VideoRates;

class CourseRooms extends Model {

    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function course() {

        return $this -> belongsTo(Course::class, "course_id", "id");

    }

    public function videos() {

        return $this -> belongsTo(Video::class, "course_id", "course_id");

    }

    public function rates() {

        return $this -> hasManyThrough(VideoRates::class, Video::class, "course_id", "video_id", "course_id", "id");

    }

}
