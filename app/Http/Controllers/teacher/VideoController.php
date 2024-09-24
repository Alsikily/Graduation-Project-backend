<?php

namespace App\Http\Controllers\teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Storage;
use Owenoj\LaravelGetId3\GetId3;

// Models
use App\Models\Video;

// Traits
use App\Traits\generalTrait;

class VideoController extends Controller {

    use generalTrait;

    public function index() {
        //
    }

    public function store(Request $request) {

        $requestData = $request -> all();

        $rules = [
            'name' => 'required|max:255|string',
            'description' => 'string',
            'video' => 'required|mimetypes:video/mp4,video/avi,video/mpeg,video/quicktime',
            'course_id' => [
                "required",
                Rule::exists('courses', 'id') -> where(function (Builder $query) {
                    return $query -> where('teacher_id', Auth::guard("teacherApi") -> user() -> id);
                }),
            ],
        ];

        $valid = $this -> valid($requestData, $rules);

        if ($valid == "true") {

            $video = Storage::disk('teacherUploads') -> put('courses/videos', $requestData['video']);
            $requestData['video'] = $video;

            $track = new GetId3(request()->file('video'));
            $length = floor($track -> getPlaytimeSeconds() / 60);
            $requestData['length'] = $length;

            $videoCourse = Video::create($requestData);
            $videoCourse -> loadSum("rates", "rate");
            $videoCourse -> loadCount("rates");

            return $this -> response([
                'status' => 'success',
                'message' => 'Video added successfully',
                'video' => $videoCourse
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

    public function destroy($video_id) {

        $video = Video::where("id", $video_id);
        $exist = $video -> get();

        if (count($exist) > 0) {

            $video -> delete();

            return $this -> response([
                "status" => "success",
                "message" => "Video deleted successfully"
            ]);

        } else {
            
            return $this -> response([
                "status" => "error",
                "message" => "Video not found"
            ]);

        }

    }

}
