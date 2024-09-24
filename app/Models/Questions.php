<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


// Models
use App\Models\Answers;

class Questions extends Model {
    use HasFactory;

    protected $guarded = [];

    public $timestamps = false;

    protected $hidden = [
        'laravel_through_key'
    ];

    public function answers() {

        return $this -> hasMany(Answers::class, "question_id", "id");

    }

}
