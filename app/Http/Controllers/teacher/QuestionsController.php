<?php

namespace App\Http\Controllers\teacher;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;

// Traits
use App\Traits\generalTrait;

// Models
use App\Models\Teacher;
use App\Models\Questions;

class QuestionsController extends Controller {

    use generalTrait;

    public function index() {

        return "ads";

    }

    public function store(Request $request) {

        $rules = [
            "exam_id" => [
                'required',
                Rule::exists('exams', 'id') -> where(function (Builder $query) {
                    return $query -> where('teacher_id', Auth::guard("teacherApi") -> user() -> id);
                }),
            ],
            "question" => "required|string",
        ];

        $valid = $this -> valid($request -> all(), $rules);

        if ($valid == "true") {

            $question = Questions::create($request -> all());
            $question -> load("answers");

            return $this -> response([
                'status' => 'success',
                'message' => 'Question added successfully',
                'question' => $question
            ]);

        } else {

            return $this -> response([
                'status' => 'error',
                'messages' => $valid
            ]);

        }

        return $request;

    }

    public function destroy($question_id) {

        // $questions = Teacher::with(["questions" => function($query) {
        //     $query -> select("questions.id");
        // }]) -> where("id", Auth::guard("teacherApi") -> user() -> id)
        //                         -> get()
        //                         -> pluck("questions");

        $question = Questions::where("id", $question_id);
        $exist = $question -> get();

        if (count($exist) > 0) {

            $question -> delete();

            return $this -> response([
                "status" => "success",
                "message" => "Question deleted successfully"
            ]);

        } else {
            
            return $this -> response([
                "status" => "error",
                "message" => "Question not found"
            ]);

        }

        // return $this -> deleteTeacherItem(Questions::class, $question_id, "Question");

    }

}
