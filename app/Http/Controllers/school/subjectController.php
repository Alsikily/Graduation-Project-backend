<?php

namespace App\Http\Controllers\school;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;

// Traits
use App\Traits\generalTrait;

// Models
use App\Models\Subject;

class subjectController extends Controller {

    use generalTrait;

    public function index() {

        $exists_subjects = Subject::with("subject") -> where("school_id", Auth::guard("schoolApi") -> user() -> id) -> get();

        return $this -> response([
            "status" => "success",
            "exists_subjects" => $exists_subjects,
        ]);

    }

    public function store(Request $request) {

        $rules = [
            "subject_id" => [
                "required",
                'exists:const_subjects,id',
                Rule::unique("subjects", "subject_id") -> where(function(Builder $query) {

                    return $query -> where("school_id", Auth::guard("schoolApi") -> user() -> id);

                }),
            ]
        ];

        $request['school_id'] = Auth::guard("schoolApi") -> user() -> id;
        $valid = $this -> valid($request -> all(), $rules);
        
        if ($valid == "true") {

            $subject = Subject::create($request -> all());
            $subject -> load("subject");

            return $this -> response([
                'status' => 'success',
                'message' => 'Subject added successfully',
                'subject' => $subject
            ]);

        }

        return $this -> response([
            'status' => 'error',
            'messages' => $valid
        ]);

    }

    public function show($id) {

        $subject = Subject::find($id);

        if ($subject) {

            return $subject;

        }

        return "no";

    }

    public function update(Request $request, $id) {
        
        

    }

    public function destroy($subject_id) {

        $subject = Subject::where("id", $subject_id) -> where("school_id", Auth::guard("schoolApi") -> user() -> id);
        $exist = $subject -> get();

        if (count($exist) > 0) {

            $subject -> delete();

            return $this -> response([
                "status" => "success",
                "message" => "Subject deleted successfully"
            ]);
            
        } else {
            
            return $this -> response([
                "status" => "error",
                "message" => "Subject not found"
            ]);

        }

    }

}
