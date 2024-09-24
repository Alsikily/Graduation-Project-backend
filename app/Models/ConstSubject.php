<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConstSubject extends Model {

    use HasFactory;

    public $table = "const_subjects";

    protected $guarded = [];

    public $timestamps = false;

}
