<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

// Models
use App\Models\Teacher;
use App\Models\BookRates;

class Book extends Model {

    use HasFactory;

    protected $guarded = [];

    public function getImageAttribute($value) {

        return asset("storage/files/teacherUploads/" . $value);

    }

    public function getBookAttribute($value) {

        return asset("storage/files/teacherUploads/" . $value);

    }

    public function getSizeAttribute($value) {

        return $value . " M";

    }

    public function teacher() {

        return $this -> belongsTo(Teacher::class, "teacher_id", "id");

    }

    public function rates() {

        return $this -> hasMany(BookRates::class, "book_id", "id");

    }

    public function getCreatedAtAttribute($value) {

        return Carbon::parse($value)->toDateString();

    }

    public function getRatesSumRateAttribute($value) {

        if ($value != null) {

            return $value;

        } else {

            return 0;

        }

    }

}
