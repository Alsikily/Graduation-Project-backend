<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Traits
use App\Traits\generalTrait;

// Models
use App\Models\Course;
use App\Models\CourseRooms;
use App\Models\Lessons;

class StudentCourseController extends Controller {

    use generalTrait;

    public function index(Request $request) {

        $rules = [
            "for" => "required", // [ class || all ]
        ];
        $valid = $this -> valid($request -> all(), $rules);

        if ($valid == "true") {

            if (request("for") == "class") {

                $courses = CourseRooms::with(["course"])
                                        -> withCount(["rates", "videos"])
                                        -> withSum("rates", 'rate')
                                        -> withSum("videos", 'length')
                                        -> where("room_id", Auth::guard("studentApi") -> user() -> room_id)
                                        -> get();

            } else if (request("for") == "all") {

                $courses = Course::where("forWhich", "all")
                                    -> withCount("rates")
                                    -> withSum("videos", 'length')
                                    -> withSum("rates", 'rate')
                                    -> withCount("videos")
                                    -> get();

            }

            return $this -> response([
                "status" => "success",
                "courses" => $courses
            ]);

            return $this -> response([
                "status" => "error"
            ]);

        }

        return $this -> response([
            "status" => "error",
            "messages" => $valid
        ]);

    }

    public function store(Request $request) {

        

    }

    public function show($id) {

        $course = Course::where("id", $id)
                        -> get();

        if (count($course) > 0) {

            $course -> load(["teacher", "videos" => function($query) {

                $query -> withSum("rates", "rate") -> withCount("rates");

            }]);
            $course -> loadSum("videos", "length");
            $course -> loadSum("videos", "views");
            $course -> loadSum("rates", "rate");
            $course -> loadCount("rates");
            $course -> loadCount("videos");

            if ($course[0] -> forWhich == "class") {

                $teacher_student = Lessons::where("teacher_id", $course[0] -> teacher_id)
                                        -> where("room_id", Auth::guard('studentApi') -> user() -> room_id)
                                        -> get();

                if (count($teacher_student) > 0) {

                    return $this -> response([
                        "status" => "success",
                        "course" => $course
                    ]);

                } else {

                    return $this -> response([
                        "status" => "error"
                    ]);

                }

            } else {

                return $this -> response([
                    "status" => "success",
                    "course" => $course
                ]);

            }

        } else {

            return $this -> response([
                "status" => "error"
            ]);

        }

    }

    public function update(Request $request, $id) {
        


    }

    public function destroy($id) {
        


    }

}



