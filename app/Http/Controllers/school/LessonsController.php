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
use App\Models\Lessons;
use App\Models\Teacher;

class LessonsController extends Controller {
    
    use generalTrait;

    public function index() {
        //
    }

    public function store(Request $request) {
        //
    }

    public function show(Lessons $lessons) {
        //
    }

    public function update(Request $request, Lessons $lesson) {

        if ($request -> input("teacher_id") != null) {

            $teacher = Teacher::where("id", $request -> input("teacher_id"))
                                    -> get();

            $lesson -> update([
                "teacher_id" => $teacher[0] -> id,
                "subject_id" => $teacher[0] -> subject_id
            ]);

        } else {

            $lesson -> update([
                "teacher_id" => null,
                "subject_id" => null
            ]);

        }

        return $this -> response([
            "status" => "success",
            "message" => "Teacher applied successfully"
        ]);

    }

    public function destroy(Lessons $lessons) {
        //
    }

}
