<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

// Models
use App\Models\Course;
use App\Models\VideoRates;

class Video extends Model {

    use HasFactory;

    protected $guarded = [];

    public function course() {

        return $this -> belongsTo(Course::class, "course_id", "id");

    }

    public function getCreatedAtAttribute($value) {

        $carbonDate = new Carbon($value);
        return $carbonDate -> diffForHumans();

    }

    public function rates() {

        return $this -> hasMany(VideoRates::class, "video_id", "id");

    }

    public function getRatesSumRateAttribute($value) {

        if ($value != null) {

            return $value;

        } else {

            return 0;

        }

    }

}
