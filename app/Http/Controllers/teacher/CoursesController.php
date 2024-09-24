<?php

namespace App\Http\Controllers\teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Database\Query\Builder;

// Traits
use App\Traits\generalTrait;

// Models
use App\Models\Course;
use App\Models\CourseRooms;

class CoursesController extends Controller {

    use generalTrait;

    public function index() {

        $courses = Course::where("teacher_id", Auth::guard("teacherApi") -> user() -> id)
                        -> withCount("videos")
                        -> get();

        return $this -> response([
            "status" => "success",
            "courses" => $courses
        ]);

    }

    public function store(Request $request) {

        $requestData = $request -> all();
        $requestData["roomsChossen"] = json_decode($request -> roomsChoosen);
        $rules = [
            'name' => 'required|max:255|string',
            'description' => '',
            'image' => 'required|image|mimes:jpg,jpeg,png,bmp',
        ];
        $requestData['teacher_id'] = Auth::guard("teacherApi") -> user() -> id;
        $requestData['subject_id'] = Auth::guard("teacherApi") -> user() -> subject_id;

        $valid = $this -> valid($requestData, $rules);

        if ($valid == "true") {

            $image = Storage::disk('teacherUploads') -> put('courses', $requestData['image']);
            $requestData['image'] = $image;

            $course = Course::create([
                "description" => $requestData["description"],
                "teacher_id" => $requestData["teacher_id"],
                "subject_id" => $requestData["subject_id"],
                "forWhich" => $requestData["forWhich"],
                "image" => $requestData["image"],
                "name" => $requestData["name"],
            ]);
            $course -> loadCount("videos");

            if ($request -> input("forWhich") == "class") {
                if ($request -> has("roomsChoosen")) {

                    $rules = [
                        'roomsChoosen' => 'required',
                        'roomsChoosen.*' => [
                            'required',
                            Rule::exists("lessons", "room_id") -> where(function (Builder $query) {
                                
                                return $query -> where('teacher_id', Auth::guard("teacherApi") -> user() -> id);
                                
                            })
                        ],
                    ];
                    
                    $valid = $this -> valid($requestData, $rules);
                    
                    if ($valid == "true") {

                        foreach(json_decode($request -> roomsChoosen) as $room) {

                            CourseRooms::create([
                                "course_id" => $course -> id,
                                "room_id" => $room,
                            ]);

                        }

                    } else {

                        return $this -> response([
                            'status' => 'error',
                            'messages' => $valid
                        ]);

                    }

                }

            }

            return $this -> response([
                'status' => 'success',
                'message' => 'Course added successfully',
                'course' => $course
            ]);

        }

        return $this -> response([
            'status' => 'error',
            'messages' => $valid
        ]);

    }

    public function show($id) {

        $course = Course::with([
                            "teacher",
                            "videos" => function ($query) {
                                            $query -> withSum("rates", "rate") -> withCount("rates");
                                        }
                            ])
                        -> withSum("videos", "views")
                        -> withSum("videos", "length")
                        -> withSum("rates", 'rate')
                        -> withCount("rates")
                        -> withCount("videos")
                        -> where("teacher_id", Auth::guard("teacherApi") -> user() -> id)
                        -> where("id", $id)
                        -> get();

        return $this -> response([
            "status" => "success",
            "course" => $course
        ]);

    }

    public function update(Request $request, $id) {
        //
    }

    public function destroy($course_id) {

        return $this -> deleteTeacherItem(Course::class, $course_id, "Course"); 

    }

}
