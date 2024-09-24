<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

// Traits
use App\Traits\generalTrait;

// Models
use App\Models\Student;
use App\Models\Parents;
use App\Models\School;
use App\Models\Teacher;

class ProfileController extends Controller {

    use generalTrait;

    public function show($AuthType) {

        if ($AuthType == "student") {

            $profile = Student::where("id", Auth::guard("studentApi") -> user() -> id)
                                -> get();

        } else if ($AuthType == "parent") {

            $profile = Parents::where("id", Auth::guard("parentApi") -> user() -> id)
                                -> get();

        } else if ($AuthType == "school") {

            $profile = School::where("id", Auth::guard("schoolApi") -> user() -> id)
                                -> get();
        } else if ($AuthType == "teacher") {

            $profile = Teacher::where("id", Auth::guard("teacherApi") -> user() -> id)
            -> get();

        }

        return response() -> json([
            "status" => "success",
            "profile" => $profile
        ]);

    }

    public function updatePhoto(Request $request, $AuthType) {

        $requestData = $request -> all();
        $rules = [
            'photo' => 'required|image|mimes:jpg,jpeg,png,bmp',
        ];

        $valid = $this -> valid($requestData, $rules);

        if ($valid == "true") {

            if ($AuthType == "student") {

                $profile = Student::where("id", Auth::guard("studentApi") -> user() -> id);
                $disk = "studentUploads";

            } else if ($AuthType == "parent") {

                $profile = Parents::where("id", Auth::guard("parentApi") -> user() -> id);
                $disk = "parentUploads";

            } else if ($AuthType == "school") {

                $profile = School::where("id", Auth::guard("schoolApi") -> user() -> id);
                $disk = "schoolUploads";

            } else if ($AuthType == "teacher") {

                $profile = Teacher::where("id", Auth::guard("teacherApi") -> user() -> id);
                $disk = "teacherUploads";

            }

            $photo = Storage::disk($disk) -> put('profile', $requestData['photo']);

            $profile -> update([
                "photo" => $photo
            ]);

            return $this -> response([
                "status" => "success",
                "user" => $profile -> get()
            ]);

        }

        return $this -> response([
            "status" => "error",
            "messages" => $valid
        ]);

    }

    public function update(Request $request, $AuthType) {

        $requestData = $request -> all();
        $rules = [
            'name' => 'required|max:100|string',
            'email' => 'required|email|max:100',
            'phone' => 'max:20',
        ];

        $valid = $this -> valid($request -> all(), $rules);
        
        if ($valid == "true") {

            if ($AuthType == "student") {

                $profile = Student::where("id", Auth::guard("studentApi") -> user() -> id);

            } else if ($AuthType == "parent") {

                $profile = Parents::where("id", Auth::guard("parentApi") -> user() -> id);

            } else if ($AuthType == "school") {

                $profile = School::where("id", Auth::guard("schoolApi") -> user() -> id);

            } else if ($AuthType == "teacher") {

                $profile = Teacher::where("id", Auth::guard("teacherApi") -> user() -> id);

            }

            if ($request -> password != null) {

                $profile -> update([
                    "name" => $requestData["name"],
                    "email" => $requestData["email"],
                    "password" => bcrypt($requestData["password"]),
                    "address" => $requestData["address"],
                    "phone" => $requestData["phone"],
                ]);

            } else {

                $profile -> update([
                    "name" => $requestData["name"],
                    "email" => $requestData["email"],
                    "address" => $requestData["address"],
                    "phone" => $requestData["phone"],
                ]);

            }

            return $this -> response([
                "status" => "success",
                "user" => $profile -> get()
            ]);

        } else {

            return $this -> response([
                "status" => "error",
                "messages" => $valid
            ]);

        }

    }

}
