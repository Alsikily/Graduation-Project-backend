<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Traits
use App\Traits\generalTrait;

// Models
use App\Models\Student;

class StudentResultController extends Controller {

    use generalTrait;

    public function index() {

        $results = Student::with(["result" => 
                                    function($query) {

                                        return $query -> groupBy("id") -> with(["results" => function($query) {
                                            $query -> where("student_id", Auth::guard("studentApi") -> user() -> id) -> orderBy("subject_id");
                                        }, "room" => ["subjects" => function($query) {
                                            $query -> orderBy("subject_id") -> with("subject");
                                        }]]);

                                    },
                                ])
                    -> where("id", Auth::guard("studentApi") -> user() -> id)
                    -> get()
                    -> pluck("result");

        return $this -> response([
            "status" => "success",
            "results" => $results[0]
        ]);

    }

}
