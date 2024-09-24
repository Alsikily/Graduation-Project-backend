<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// Models
use App\Models\Course;

class Cart extends Model {

    use HasFactory;

    public $timestamps = false;

    protected $guarded = [];

    public function course() {

        return $this -> belongsTo(Course::class, "course_id", "id");

    }

}
