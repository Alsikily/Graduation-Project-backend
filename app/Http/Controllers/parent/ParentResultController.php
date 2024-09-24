<?php

namespace App\Http\Controllers\parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Traits
use App\Traits\generalTrait;

// Models
use App\Models\Parents;

class ParentResultController extends Controller {

    use generalTrait;

    public function index(Request $request) {

        $results = Parents::with(["students" => function($query) {

                                                    $query -> where("id", request("student_id")) -> with(["result" =>
                                                    function($query) {
                
                                                        return $query -> groupBy("id") -> with(["results" => function($query) {
                                                            $query -> where("student_id", request("student_id")) -> orderBy("subject_id");
                                                        }, "room" => ["subjects" => function($query) {
                                                            $query -> orderBy("subject_id") -> with("subject");
                                                        }]]);
                
                                                    }]);
                                                }
                                ])
                    -> where("id", Auth::guard("parentApi") -> user() -> id)
                    -> get() -> pluck("students");

        return $this -> response([
            "status" => "success",
            "results" => $results
        ]);

    }

}
