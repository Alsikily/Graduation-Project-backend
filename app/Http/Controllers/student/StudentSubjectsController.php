<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Traits
use App\Traits\generalTrait;

// Models
use App\Models\Student;

class StudentSubjectsController extends Controller {

    use generalTrait;
    
    public function index() {

        // $subjects = Student::select("room_id")
        //                     -> with(["subjects" => ["Subject" => ["subject"]]])
        //                     -> where("id", Auth::guard("studentApi") -> user() -> id)
        //                     -> get()
        //                     -> pluck("Subjects");

        $subjects = Student::select("room_id")
                            -> with(["subjects" => ["Subject" => function($query) {
                                $query -> select("id", "subject_id") -> with("subject");
                            }]])
                            -> where("id", Auth::guard("studentApi") -> user() -> id)
                            -> get()
                            -> pluck("subjects.*.Subject");

        return $this -> response([
            "status" => "success",
            "subjects" => $subjects[0]
        ]);

        // return $this -> response([
        //     "status" => "success",
        //     "subjects" => $subjects[0] -> subjects
        // ]);

    }

}
