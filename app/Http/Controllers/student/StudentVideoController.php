<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Traits
use App\Traits\generalTrait;

// Models
use App\Models\Course;
use App\Models\Lessons;
use App\Models\Video;
use App\Models\VideoRates;

class StudentVideoController extends Controller {

    use generalTrait;

    public function index() {
        //
    }

    public function store(Request $request) {

        $VideoRate = VideoRates::where("video_id", $request -> video_id)
                            -> where("student_id", Auth::guard("studentApi") -> user() -> id);

        $exist = $VideoRate -> first();

        if ($exist != null) {

            $VideoRate -> update([
                "rate" => $request -> rate
            ]);

        } else {

            VideoRates::create([
                "video_id" => $request -> video_id,
                "student_id" => Auth::guard("studentApi") -> user() -> id,
                "rate" => $request -> rate
            ]);

        }

        return $this -> response([
            "status" => "success",
        ]);

    }

    public function show($id) {

        $video = Video::find($id);
        
        if ($video) {

            $studentRate = VideoRates::where("student_id", Auth::guard("studentApi") -> user() -> id)
                                        -> where("video_id", $id)
                                        -> first();

            $video["studentRate"] = $studentRate;
            $video -> load([
                "course" => function($query) {
                    $query -> withCount("videos") -> with("videos");
                }
            ]);

            $course = Course::where("id", $video -> course_id) -> get();

            if (count($course) > 0) {

                if ($course[0] -> forWhich == "class") {

                    $teacher_student = Lessons::where("teacher_id", $course[0] -> teacher_id)
                                            -> where("room_id", Auth::guard('studentApi') -> user() -> room_id)
                                            -> get();

                    if (count($teacher_student) > 0) {

                        return $this -> response([
                            "status" => "success",
                            "video" => $video
                        ]);

                    } else {

                        return $this -> response([
                            "status" => "error"
                        ]);

                    }

                } else {

                    return $this -> response([
                        "status" => "success",
                        "video" => $video
                    ]);

                }

            } else {

                return $this -> response([
                    "status" => "error"
                ]);

            }

        } else {

            return $this -> response([
                "status" => "error"
            ]);

        }

    }

    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
