<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Models
use App\Models\ConstClasses;
use App\Models\Rooms;

class Classes extends Model {
    use HasFactory;

    protected $guarded = [];

    public function ItsClass() {

        return $this -> belongsTo(ConstClasses::class, 'class_id', 'id');

    }

    public function rooms() {

        return $this -> hasMany(Rooms::class, 'class_id', 'id');

    }

    public function students() {

        return $this -> rooms() -> withCount("students");

    }

}
