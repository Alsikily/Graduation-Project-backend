<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class TeacherAbsences extends Model {

    use HasFactory;

    protected $guarded = [];

    public function getCreatedAtAttribute($value) {

        $carbon = new Carbon();
        return Carbon::parse($value)->format('d');

    }

    public $timestamps = false;

}
