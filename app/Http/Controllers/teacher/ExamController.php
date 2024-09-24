<?php

namespace App\Http\Controllers\teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;

// Traits
use App\Traits\generalTrait;

// Models
use App\Models\Exam;
use App\Models\ExamRooms;

class ExamController extends Controller {

    use generalTrait;

    public function index() {

        $exams = Exam::where("teacher_id", Auth::guard("teacherApi") -> user() -> id)
                        -> withCount("questions")
                        -> get();

        return $this -> response([
            "status" => "success",
            "exams" => $exams
        ]);

    }

    public function store(Request $request) {
        $rules = [
            'name' => 'required|max:255|string',
            'degree' => 'required|numeric',
            'for' => 'required|max:5|string',
        ];
        $valid = $this -> valid($request -> all(), $rules);
        if ($valid == "true") {
            $request['teacher_id'] = Auth::guard("teacherApi") -> user() -> id;
            $request['subject_id'] = Auth::guard("teacherApi") -> user() -> subject_id;
            $exam = Exam::create($request -> only(["name", "degree", "for", "teacher_id", "subject_id"]));
            if ($request -> input("for") == "class") {
                if ($request -> has("roomsChoosen")) {
                    $rules = [
                        'roomsChoosen' => 'required|array',
                        'roomsChoosen.*' => [
                            'required',
                            Rule::exists("lessons", "room_id") -> where(function (Builder $query) {
                                return $query -> where('teacher_id', Auth::guard("teacherApi") -> user() -> id);
                            })
                        ],
                    ];
                    $valid = $this -> valid($request -> all(), $rules);
                    if ($valid == "true") {
                        foreach($request -> input("roomsChoosen") as $room) {
                            ExamRooms::create([
                                "exam_id" => $exam -> id,
                                "room_id" => $room,
                            ]);
                        }
                    } else {
                        return $this -> response([
                            'status' => 'error',
                            'messages' => $valid
                        ]);
                    }
                }
            }
            $exam -> loadCount("questions");
            return $this -> response(['status' => 'success','message' => 'Exam added successfully','exam' => $exam]);
        }
        return $this -> response(['status' => 'error', 'messages' => $valid]);
    }

    public function show($exam_id) {

        $exam = Exam::with(["questions" => ["answers"]])
                    -> where("teacher_id", Auth::guard("teacherApi") -> user() -> id)
                    -> where("id", $exam_id)
                    -> get();

        return $this -> response([
            "status" => "success",
            "exam" => $exam
        ]);

    }

    public function update(Request $request, Exam $exam) {



    }

    public function destroy($exam_id) {

        $exam = Exam::where("id", $exam_id) -> where("teacher_id", Auth::guard("teacherApi") -> user() -> id);
        $exist = $exam -> get();

        if (count($exist) > 0) {

            $exam -> delete();

            return $this -> response([
                "status" => "success",
                "message" => "Exam deleted successfully"
            ]);

        } else {
            
            return $this -> response([
                "status" => "error",
                "message" => "Exam not found"
            ]);

        }

    }

}
