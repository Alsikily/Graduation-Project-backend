<?php

namespace App\Http\Controllers\parent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;

// Traits
use App\Traits\generalTrait;

// Models
use App\Models\Student;

class ParentChildrenController extends Controller {

    use generalTrait;

    public function index() {

        $children = Student::where("parent_id", Auth::guard("parentApi") -> user() -> id)
                    -> get();

        return $this -> response([
        "status" => "success",
        "children" => $children
        ]);

    }

    public function store(Request $request) {
        
        $rules = [
            'email' => [
                'required',
                Rule::exists('students', 'email') -> where(function (Builder $query) {
                    return $query -> whereNull('parent_id');
                }),
            ]
        ];

        $valid = $this -> valid($request -> all(), $rules);

        if ($valid == "true") {

            $request['parent_id'] = Auth::guard("parentApi") -> user() -> id;
            $childrenInfo = Student::where("email", request("email"));
            $childrenInfo -> update([
                "parent_id" => $request['parent_id'],
            ]);

            return $this -> response([
                'status' => 'success',
                'message' => 'Children added successfully',
                'child' => $childrenInfo -> get()
            ]);

        }

        return $this -> response([
            'status' => 'error',
            'messages' => $valid
        ]);

    }

    public function show($id) {
        //
    }

    public function update(Request $request, $id) {
        //
    }

    public function destroy($children) {

        // $item = $model::where("id", $item_id) -> where("teacher_id", Auth::guard("teacherApi") -> user() -> id);
        // $exist = $item -> get();

        // if (count($exist) > 0) {

        //     $item -> delete();

        //     return $this -> response([
        //         "status" => "success",
        //         "message" => "$itemName deleted successfully"
        //     ]);

        // } else {

        //     return $this -> response([
        //         "status" => "error",
        //         "message" => "$itemName not found"
        //     ]);

        // }

        $children = Student::where("id", $children)
                                -> where("parent_id", Auth::guard("parentApi") -> user() -> id);
        $exist = $children -> get();
        if (count($exist) > 0) {

            $children -> update([
                "parent_id" => null,
            ]);

            return $this -> response([
                "status" => "success",
                "message" => "Child deleted successfully"
            ]);

        } else {

            return $this -> response([
                "status" => "error",
                "message" => "Child not found"
            ]);
            
        }

    }

}
