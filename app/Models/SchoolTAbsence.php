<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class SchoolTAbsence extends Model {

    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    public function getCreatedAtAttribute($value) {

        $carbon = new Carbon();
        return Carbon::parse($value)->format('d');

    }

}
