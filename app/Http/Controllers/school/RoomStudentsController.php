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
use App\Models\Student;
use App\Models\Rooms;

class RoomStudentsController extends Controller {

    use generalTrait;

    public function index() {

        $students = 'ss';

        

        return "AA";
        // $students = Student::where("")

    }

    public function store(Request $request) {
        
        $rules = [
            "email" => [
                "required",
                Rule::exists("students", "email") -> where(function(Builder $query) {

                    return $query -> whereNull("school_id");

                })
            ],
            "room_id" => [
                "required",
                Rule::exists("rooms", "id") -> where(function(Builder $query) {

                    return $query -> where("school_id", Auth::guard("schoolApi") -> user() -> id);

                })
            ]
        ];

        $valid = $this -> valid($request -> all(), $rules);
        
        if ($valid == "true") {
            
            $student = Student::where("email", request("email"));

            $student -> update([
                "school_id" => Auth::guard("schoolApi") -> user() -> id,
                "room_id" => $request -> input("room_id")
            ]);

            return $this -> response([
                'status' => 'success',
                'message' => 'Student added successfully',
                'student' => $student -> get(),
            ]);

        }

        return $this -> response([
            'status' => 'error',
            'messages' => $valid
        ]);

        return "";

    }

    public function show($id) {

        $students = Rooms::with(["students"])
                            -> where("school_id", Auth::guard("schoolApi") -> user() -> id)
                            -> where("id", $id)
                            -> get();

        return $this -> response([
            "status" => "success",
            "students" => $students
        ]);

    }

    public function update(Request $request, $id) {



    }

    public function destroy($id) {

        $student = Student::where("id", $id)
                            -> where("school_id", Auth::guard("schoolApi") -> user() -> id);
        $exist = $student -> first();

        if ($exist != null) {

            $student -> update([
                "school_id" => null,
                "room_id" => null,
            ]);

            return $this -> response([
                "status" => "success",
                "message" => "Student deleted successfully"
            ]);

        } else {

            return $this -> response([
                "status" => "error",
                "message" => "Student not found"
            ]);

        }

    }

}
