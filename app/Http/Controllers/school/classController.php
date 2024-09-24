<?php

namespace App\Http\Controllers\school;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;

// Models
use App\Models\Classes;
use App\Models\ConstClasses;

// Traits
use App\Traits\generalTrait;

class classController extends Controller {

    use generalTrait;

    public function index() {

        $classes = Classes::with(["ItsClass"])
                    -> withCount([
                        "rooms",
                    ])
                    -> where("school_id", Auth::guard("schoolApi") -> user() -> id)
                    -> get();

        return $this -> response([
            'status' => 'success',
            'classes' => $classes
        ]);

    }

    public function store(Request $request) {

        $rules = [
            'class_id' => [
                'required',
                'exists:const_classes,id',
                Rule::unique('classes', 'class_id') -> where(function (Builder $query) {
                    return $query -> where('school_id', Auth::guard("schoolApi") -> user() -> id);
                }),
            ]
        ];

        $request['school_id'] = Auth::guard("schoolApi") -> user() -> id;
        $valid = $this -> valid($request -> all(), $rules);

        if ($valid == "true") {

            $class = Classes::create($request -> all());
            $class -> load("ItsClass");
            $class -> loadCount("rooms");

            return $this -> response([
                'status' => 'success',
                'message' => 'Class added successfully',
                'class' => $class
            ]);

        } else {

            return $this -> response([
                'status' => 'error',
                'messages' => $valid
            ]);

        }

    }

    public function show($id) {

        $students = Classes::with(["students"])
                            -> where("school_id", Auth::guard("schoolApi") -> user() -> id)
                            -> where("class_id", $id)
                            -> get();

        return $this -> response([
            "status" => "success",
            "students" => $students
        ]);

    }

    public function update(Request $request, $id) {
        
    }

    public function destroy($class_id) {

        $class = Classes::where("id", $class_id) -> where("school_id", Auth::guard("schoolApi") -> user() -> id);
        $exist = $class -> get();

        if (count($exist) > 0) {

            $class -> delete();

            return $this -> response([
                "status" => "success",
                "message" => "Class deleted successfully"
            ]);
            
        } else {
            
            return $this -> response([
                "status" => "error",
                "message" => "Class not found"
            ]);

        }

    }

}
