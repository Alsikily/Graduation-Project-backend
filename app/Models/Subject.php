<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

// Models
use App\Models\ConstSubject;
use App\Models\Exam;

class Subject extends Model {
    use HasFactory;

    protected $guarded = [];

    public function getImageAttribute($value) {

        return asset("storage/files/schoolUploads/" . $value);

    }

    public function subject() {

        return $this -> belongsTo(ConstSubject::class, "subject_id", "id");

    }

    public function exams() {

        return $this -> hasMany(Exam::class, "subject_id", "id");

    }

}
