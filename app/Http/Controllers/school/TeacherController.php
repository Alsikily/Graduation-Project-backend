<?php

namespace App\Http\Controllers\school;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;

// Traits
use App\Traits\generalTrait;

// Models
use App\Models\Teacher;
use App\Models\TeacherSubject;

class TeacherController extends Controller {

    use generalTrait;

    public function index(Request $request) {

        $teachers = Teacher::with([
                                "subject" => [
                                    "subject"
                                ]
                            ])
                            -> where("school_id", Auth::guard("schoolApi") -> user() -> id)
                            -> get();

        return $this -> response([
            "status" => "success",
            "teachers" => $teachers
        ]);

    }

    public function store(Request $request) {

        $rules = [
            'teacher_email' => [
                'required',
                'exists:teachers,email',
            ],
            'subject_id' => [
                'required',
                Rule::exists('subjects', 'id') -> where(function (Builder $query) {
                    return $query -> where('school_id', Auth::guard("schoolApi") -> user() -> id);
                }),
            ]
        ];

        $valid = $this -> valid($request -> all(), $rules);

        if ($valid == "true") {

            $request['school_id'] = Auth::guard("schoolApi") -> user() -> id;
            $teacherInfo = Teacher::with(["subject" => ["subject"]]) -> where("email", request("teacher_email"));
            $teacherInfo -> update([
                "subject_id" => $request['subject_id'],
                "school_id" => $request['school_id'],
            ]);

            return $this -> response([
                'status' => 'success',
                'message' => 'Teacher added successfully',
                'teacher' => $teacherInfo -> get()
            ]);

        }

        return $this -> response([
            'status' => 'error',
            'messages' => $valid
        ]);

    }

    public function destroy($teacher_id) {

        $teacher = Teacher::where("id", $teacher_id)
                                -> where("school_id", Auth::guard("schoolApi") -> user() -> id);
        $exist = $teacher -> first();

        if ($exist != null) {

            $teacher -> update([
                "subject_id" => null,
                "school_id" => null,
            ]);

            return $this -> response([
                "status" => "success",
                "message" => "Teacher deleted successfully"
            ]);

        } else {

            return $this -> response([
                "status" => "error",
                "message" => "Teacher not found"
            ]);

        }

    }

}
