<?php

namespace App\Http\Controllers\school;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Traits
use App\Traits\generalTrait;

// Models
use App\Models\Rooms;
use App\Models\Result;
use App\Models\ResultStudent;

class SchoolResultsController extends Controller {

    use generalTrait;

    public function index(Request $request) {

        if ($request -> status == "announced") {

            // $rooms = Rooms::with(["subjects" => ["subject"], "students"])
            //                 -> where("school_id", Auth::guard("schoolApi") -> user() -> id)
            //                 -> whereIn('id', function($query) {
            //                                 $query -> select('room_id') -> from('results');
            //                             })
            //                 -> get();

            $rooms = Result::with(["room" => [
                                    "subjects" => function($query) {
                                        $query -> orderBy("id") -> with("subject");
                                    },
                                    "students" => ["degrees" => function($query) {
                                        $query -> orderBy("subject_id");
                                    }]
                                ]])
                            -> where("school_id", Auth::guard("schoolApi") -> user() -> id)
                            -> get();

            // foreach($rooms as $room) {

            //     $room -> load([
            //         "students" => function($query) use($room) {

            //             $query -> where("result_id", $room -> id);

            //         }
            //     ]);

            // }

        } else if ($request -> status == "not_announced") {

            $rooms = Rooms::with(["subjects" => ["subject"], "students"])
                            -> where("school_id", Auth::guard("schoolApi") -> user() -> id)
                            -> whereNotIn('id', function($query) {
                                            $query -> select('room_id') -> from('results');
                                        })
                            -> whereIn('id', function($query) {
                                            $query -> select('room_id') -> from('lessons');
                                        })
                            -> get();

        }

        return $this -> response([
            "status" => "success",
            "rooms" => $rooms
        ]);

    }

    public function store(Request $request) {

        $examName = $request -> name;
        $roomId = $request -> room_id;

        $resultCreated = Result::create([
            "name" => $examName,
            "school_id" => Auth::guard("schoolApi") -> user() -> id,
            "room_id" => $roomId
        ]);

        foreach ($request -> results as $result) {

            $studentId = $result[0][0];

            foreach ($result[1] as $row) {

                ResultStudent::create([
                    "subject_degree" => $row[1],
                    "student_degree" => $row[2],
                    "student_id" => $studentId,
                    "result_id" => $resultCreated -> id,
                    "subject_id" => $row[0],
                ]);

            }

        }

        return $this -> response([
            "status" => "success"
        ]);

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
