<?php

namespace App\Http\Controllers\teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Traits
use App\Traits\generalTrait;

// Models
use App\Models\Lessons;
use App\Models\TeacherTAbsence;
use App\Models\TeacherAbsences;
use App\Models\Student;

class TeacherAbsencesController extends Controller {

    use generalTrait;

    public function index() {

        $rooms = Lessons::with(["room" => [
                                        "students" => ["absences" => function ($query) {
                                            $query -> whereMonth("created_at", date("m")) -> orderBy("created_at");
                                        }],
                                        "absence" => function($query) {
                                            $query -> whereMonth("created_at", date("m")) -> orderBy("created_at");
                                        }
                                    ]
                                ]
                            )
                        -> where("teacher_id", Auth::guard("teacherApi") -> user() -> id)
                        -> groupBy("room_id")
                        -> get()
                        -> pluck("room");

        return $this -> response([
            "status" => "success",
            "rooms" => $rooms
        ]);

    }

    public function store(Request $request) {

        $rules = [
            'room_id' => 'required|exists:rooms,id',
        ];

        $valid = $this -> valid($request -> all(), $rules);

        if ($valid == "true") {

            if ($request -> has("students")) {

                $lastTakeAbsence = TeacherTAbsence::selectRaw("date(created_at) as date")
                                                    -> where('teacher_id', Auth::guard("teacherApi") -> user() -> id)
                                                    -> where('room_id', request("room_id"))
                                                    -> orderBy("created_at", "DESC")
                                                    -> first();

                $allowed = !$lastTakeAbsence ? true : ($lastTakeAbsence["date"] != date("Y-m-d") ? true : false);

                if ($allowed) {

                    $absence = TeacherTAbsence::create([
                        "room_id" => $request -> room_id,
                        "teacher_id" => Auth::guard("teacherApi") -> user() -> id,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
        
                    $studentsAbsences = [];
        
                    foreach($request -> students as $student_id) {
    
                        $exist = Student::where("id", $student_id) -> get();
    
                        if ($exist) {
    
                            $studentAbsence = TeacherAbsences::create([
                                "student_id" => $student_id,
                                "take_id" => $absence -> id,
                                "room_id" => $request -> room_id,
                                "status" => "test",
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
    
                            $studentsAbsences[] = $studentAbsence;
    
                        } else {
    
                            return $this -> response([
                                "status" => "error",
                                "message" => "Student $student_id not found"
                            ]);
    
                        }
    
        
                    }
        
                    return $this -> response([
                        "status" => "success",
                        "absence" => $absence,
                        "studentsAbsences" => $studentsAbsences,
                    ]);

                } else {

                    return $this -> response([
                        'status' => 'error',
                        'message' => 'Absence has been taken today'
                    ]);

                }

            } else {

                return $this -> response([
                    "status" => "error",
                    "messages" => "No students found"
                ]);

            }

        } else {

            return $this -> response([
                "status" => "error",
                "messages" => $valid
            ]);

        }

    }

    public function show($id) {



    }

    public function update(Request $request, $id) {



    }

    public function destroy($id) {



    }

}
