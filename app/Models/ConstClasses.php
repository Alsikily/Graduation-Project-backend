<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConstClasses extends Model {

    use HasFactory;

    public $table = "const_classes";

    protected $guarded = [];

    public $timestamps = false;

}
