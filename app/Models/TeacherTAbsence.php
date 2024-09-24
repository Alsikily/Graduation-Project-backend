<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

// Models
use App\Models\TeacherAbsences;

class TeacherTAbsence extends Model {

    use HasFactory;

    protected $guarded = [];

    public function getCreatedAtAttribute($value) {

        $carbon = new Carbon();
        return Carbon::parse($value)->format('d');

    }

    public function absences() {

        return $this -> hasMany(TeacherAbsences::class, "take_id", "id");

    }

    public $timestamps = false;

}
