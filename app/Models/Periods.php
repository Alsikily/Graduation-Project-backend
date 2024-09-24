<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lessons;
use App\Models\Days;
use App\Models\Rooms;

class Periods extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function Lessons() {

        return $this -> hasMany(Lessons::class, 'period_id', 'id') -> orderBy('day_id', "ASC");

    }

    public $timestamps = false;

}
