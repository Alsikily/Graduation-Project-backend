<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

// Models
use App\Models\Teacher;
use App\Models\Video;
use App\Models\VideoRates;

class Course extends Model {

    use HasFactory;

    protected $guarded = [];

    public function teacher() {

        return $this -> belongsTo(Teacher::class, "teacher_id", "id");

    }

    public function getImageAttribute($value) {

        return asset("storage/files/teacherUploads/" . $value);

    }

    public function videos() {

        return $this -> hasMany(Video::class, "course_id", "id");

    }

    public function getCreatedAtAttribute($value) {

        $carbonDate = new Carbon($value);
        $createdAt = $carbonDate -> diffForHumans();
        return [
            "createdAt" => $createdAt,
            "createdAtDate" => Carbon::parse($value)->toDateString()
        ];

    }

    public function rates() {

        return $this -> hasManyThrough(VideoRates::class, Video::class, "course_id", "video_id");

    }

    public function getRatesSumRateAttribute($value) {

        if ($value != null) {

            return $value;

        } else {

            return 0;

        }

    }

    public function getVideosSumViewsAttribute($value) {

        if ($value != null) {

            return $value;

        } else {

            return 0;

        }

    }

    public function getVideosSumLengthAttribute($value) {

        if ($value != null) {

            return $value;

        } else {

            return 0;

        }

    }

}
