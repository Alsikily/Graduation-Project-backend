<?php

namespace App\Http\Controllers\school;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Traits
use App\Traits\generalTrait;

// Models
use App\Models\ConstClasses;
use App\Models\Classes;
use App\Models\ConstSubject;
use App\Models\Subject;
use App\Models\Days;

class SchoolController extends Controller {

    use generalTrait;

    public function const_classes() {

        $schoolClasses = Classes::select("class_id")
                                    -> where("school_id", Auth::guard("schoolApi") -> user() -> id)
                                    -> get();

        $const_classes = ConstClasses::whereNotIn("id", $schoolClasses) -> get();

        return $this -> response([
            'status' => 'success',
            'const_classes' => $const_classes
        ]);

    }

    public function const_subjects() {

        $schoolSubjects = Subject::select("subject_id")
                                    -> where("school_id", Auth::guard("schoolApi") -> user() -> id)
                                    -> get();
        $const_subjects = ConstSubject::whereNotIn("id", $schoolSubjects) -> get();

        return $this -> response([
            "status" => "success",
            "const_subjects" => $const_subjects,
        ]);

    }

    public function days() {

        $days = Days::select("day") -> get();

        return $this -> response([
            "status" => "success",
            "days" => $days
        ]);

    }

}
