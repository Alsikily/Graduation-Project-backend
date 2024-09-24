<?php

namespace App\Http\Controllers\parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Traits
use App\Traits\generalTrait;

// Models
use App\Models\Student;

class ParentExamsController extends Controller {

    use generalTrait;
    public function index() {
        
        $exams = Student::with(["exams" => ["exam" => ["subject" => ["subject"]]]])
                        -> withSum("exams", "exam_degree")
                        -> withSum("exams", "student_degree")
                        -> where("parent_id", Auth::guard("parentApi") -> user() -> id)
                        -> get();

        return $this -> response([
            "status" => "success",
            "exams" => $exams
        ]);

    }

    public function store(Request $request) {
        //
    }

    public function show($id) {
        //
    }

    public function update(Request $request, $id) {
        //
    }

    public function destroy($id) {
        //
    }

}
