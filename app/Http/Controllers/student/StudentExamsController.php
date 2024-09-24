<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Traits
use App\Traits\generalTrait;

// Models
use App\Models\Exam;
use App\Models\Answers;
use App\Models\ExamRooms;
use App\Models\StudentsExams;


class StudentExamsController extends Controller {

    use generalTrait;

    public function index(Request $request) {

        $rules = [
            "teacher" => "required", // [ class || all ]
            "exam" => "required", // [ exams || success || faild ]
        ];
        $valid = $this -> valid($request -> all(), $rules);

        if ($valid == "true") {

            if (request("exam") == "exams") {

                if (request("teacher") == "class") {

                    $exams = ExamRooms::with(["exam" => function ($query) {
                                        $query -> withCount("questions") -> having('questions_count', '>', 0);
                                    }])
                                    -> where("room_id", Auth::guard("studentApi") -> user() -> room_id)
                                    -> whereNotIn("exam_id", StudentsExams::select("exam_id") -> where("student_id", Auth::guard("studentApi") -> user() -> id))
                                    -> get()
                                    -> pluck("exam");

                } else if (request("teacher") == "all") {

                    $exams = Exam::where("for", "all")
                                    -> withCount("questions")
                                    -> whereNotIn("id", StudentsExams::select("exam_id") -> where("student_id", Auth::guard("studentApi") -> user() -> id))
                                    -> having('questions_count', '>', 0)
                                    -> get();

                }

                return $this -> response([
                    "status" => "success",
                    "exams" => $exams
                ]);

            } else if (request("exam") == "success" || request("exam") == "faild") {

                if (request("teacher") == "class" || request("teacher") == "all") {

                    $exams = StudentsExams::with("exam")
                                        -> where("student_id", Auth::guard("studentApi") -> user() -> id)
                                        -> where("status", request("exam"))
                                        -> where("for", request("teacher"))
                                        -> get();

                    return $this -> response([
                        "status" => "success",
                        "exams" => $exams
                    ]);

                }

                return $this -> response([
                    "status" => "errorss"
                ]);

            }

            return $this -> response([
                "status" => "error"
            ]);

        }

        return $this -> response([
            "status" => "error",
            "messages" => $valid
        ]);

    }

    public function show($id) {

        $studentExam = StudentsExams::where("exam_id", $id)
                                        -> where("student_id", Auth::guard("studentApi") -> user() -> id)
                                        -> first();

        if ($studentExam == null) {
            
            $exam = Exam::with([
                            "questions" => [
                                "answers"
                            ],
                            "teacher" => function($query)  {
                                $query -> select("id", "name");
                            },
                        ])
                        -> withCount('StudentExam')
                        -> where("id", $id)
                        -> get();
    
            return $this -> response([
                "status" => "success",
                "exam" => $exam
            ]);

        } else {

            return $this -> response([
                "status" => "error",
            ]);

        }

    }

    public function store(Request $request, $id) {
        $rules = [ 'answers' => 'array' ];
        $valid = $this -> valid($request -> all(), $rules);
        if ($valid == "true") {
            $studentExam = StudentsExams::where("student_id", Auth("studentApi") -> user() -> id)
                                        -> where("exam_id", $id);
            $CheckStudentExam = $studentExam -> get();
            $exam = Exam::withCount("questions")
                        -> where("id", $id)
                        -> get();
                        $question_degree = $exam[0] -> degree / $exam[0] -> questions_count;
            if (count($CheckStudentExam) == 0 && count($exam) > 0) {
                $student = Auth::guard("studentApi") -> user();
                $studentDegree = 0;
                foreach($request -> answers as $Single_Answer) {
                    $answer = Answers::where("id", $Single_Answer[1]) -> get();
                    if ($answer[0] -> isTrue == 'yes') {
                        $studentDegree += $question_degree;
                    }
                }
                $updated = $studentExam -> increment("student_degree", $studentDegree);
                $student -> increment("rank", $studentDegree);
                $studentStatus = $studentDegree >= ($exam[0] -> degree / 2) ? "success" : "faild" ;
                $CreateStudentExam = StudentsExams::create([
                    "exam_degree" => $exam[0] -> degree,
                    "student_degree" => round($studentDegree, 1),
                    "status" => $studentStatus,
                    "for" => $exam[0] -> for,
                    "exam_id" => $exam[0] -> id,
                    "student_id" => Auth::guard("studentApi") -> user() -> id,
                ]);
                return $this -> response([
                    'status' => 'success',
                    'exam' => $studentExam -> get()
                ]);
            } else {
                return $this -> response([
                    'status' => 'error',
                    'message' => 'Exam not found'
                ]);
            }
        }
        return $this -> response([
            'status' => 'error',
            'message' => $valid
        ]);
    }
}
