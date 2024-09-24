<?php

namespace App\Http\Controllers\parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Traits
use App\Traits\generalTrait;

// Models
use App\Models\Rooms;
use App\Models\Student;

class ParentAbsenceController extends Controller {

    use generalTrait;

    public function index(Request $request) {

        $student = Student::find(request("student_id"));

        if ($student) {

            $room = Rooms::with(
                                    [
                                        "students" => function($query) {
                                            $query -> where("id", request("student_id")) -> with(["absences" => function ($query) {
                                                $query -> whereMonth("created_at", date("m")) -> orderBy("created_at");
                                            }]);
                                        },
                                        "absence" => function($query) {
                                            $query -> whereMonth("created_at", date("m")) -> orderBy("created_at");
                                        }
                                    ]
                                )
                            -> where("id", $student -> room_id)
                            -> get();
    
            return $this -> response([
            "status" => "success",
            "room" => $room
            ]);

        }

    }

    public function store(Request $request) {



    }

    public function show($id) {



    }

    public function update(Request $request, $id) {



    }

    public function destroy($id) {



    }

}
