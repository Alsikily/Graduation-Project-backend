<?php

namespace App\Http\Controllers\teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;

// Traits
use App\Traits\generalTrait;
    
// Models
use App\Models\Answers;

class AnswersController extends Controller {

    use generalTrait;

    public function index() {
        //
    }

    public function store(Request $request) {

        $rules = [
            "answer" => 'required|string',
            'question_id' => [
                'required',
                Rule::exists('questions', 'id') -> where(function (Builder $query) {
                    return $query -> where('exam_id', request('exam_id'));
                }),
            ],
        ];

        $valid = $this -> valid($request -> all(), $rules);

        if ($valid == "true") {

            $answer = Answers::create($request -> only("answer", "question_id"));

            return $this -> response([
                'status' => 'success',
                'message' => 'Answer added successfully',
                'answer' => $answer
            ]);

        } else {

            return $this -> response([
                'status' => 'error',
                'messages' => $valid
            ]);

        }

    }

    public function show($id) {
        //
    }

    public function update(Request $request, $id) {
        
        

    }

    public function updateTrue(Request $request) {

        $rules = [
            "exam_id" => [
                'required',
                Rule::exists('exams', 'id') -> where(function (Builder $query) {
                    return $query -> where('teacher_id', Auth::guard("teacherApi") -> user() -> id);
                }),
            ],
            'question_id' => [
                'required',
                Rule::exists('questions', 'id') -> where(function (Builder $query) {
                    return $query -> where('exam_id', request('exam_id'));
                }),
            ],
            "answer_id" => [
                'required',
                Rule::exists('answers', 'id') -> where(function (Builder $query) {
                    return $query -> where('question_id', request('question_id'));
                }),
            ]
        ];

        $valid = $this -> valid($request -> all(), $rules);

        if ($valid == "true") {

            $answer = Answers::where("question_id", $request -> input("question_id"));
            $answer_collection = $answer;

            $answer -> update([
                "isTrue" => NULL
            ]);

            $answer_collection -> where("id", $request -> input("answer_id"))
                                -> update([
                                    "isTrue" => 'yes'
                                ]);

            return $this -> response([
                'status' => 'success',
                'message' => 'answer selected successfully'
            ]);

        } else {

            return $this -> response([
                'status' => 'error',
                'messages' => $valid
            ]);

        }

    }

    public function destroy($id) {
        //
    }

}
