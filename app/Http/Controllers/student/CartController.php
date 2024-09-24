<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Traits
use App\Traits\generalTrait;

// Models
use App\Models\Cart;

class CartController extends Controller {

    use generalTrait;

    public function index() {

        $courses = Cart::with(["course" => function ($query) {
                            $query -> withCount(["videos", "rates"])
                            -> withSum("rates", 'rate')
                            -> withSum("videos", 'length');
                        }])
                        -> where("student_id", Auth::guard("studentApi") -> user() -> id)
                        -> get()
                        -> pluck("course");

        return $this -> response([
            "status" => "success",
            "courses" => $courses
        ]);

    }

    public function store(Request $request) {

        $user_id = Auth::guard("studentApi") -> user() -> id;
        $cart = Cart::where("course_id", request("course_id"))
                        -> where("student_id", $user_id);
        $exist = $cart -> first();

        if ($exist) {

            $cart -> delete();

            return $this -> response([
                "status" => "success",
                "exist" => false
            ]);

        } else {

            Cart::create([
                "course_id" => request("course_id"),
                "student_id" => $user_id,
            ]);

            return $this -> response([
                "status" => "success",
                "exist" => true
            ]);

        }

    }

    public function show($id) {
        
        $user_id = Auth::guard("studentApi") -> user() -> id;
        $cart = Cart::where("course_id", $id)
                        -> where("student_id", $user_id);
        $exist = $cart -> first();

        if ($exist) {

            return $this -> response([
                "status" => "success",
                "exist" => true
            ]);

        }

        return $this -> response([
            "status" => "success",
            "exist" => false
        ]);

    }

    public function update(Request $request, $id) {
        //
    }

    public function destroy($id) {
        //
    }

}
