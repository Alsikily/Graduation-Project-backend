<?php

namespace App\Http\Controllers\school;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;
use App\Models\Rooms;

// Traits
use App\Traits\generalTrait;

class RoomController extends Controller {

    use generalTrait;

    public function index(Request $request) {

        if ($request -> has("getter")) {

            $rooms = Rooms::with([
                                'Periods' => [
                                    'Lessons' => [
                                        'Teacher',
                                        'Subject'
                                    ]
                                ]
                            ]
                        ) -> where("school_id", Auth::guard("schoolApi") -> user() -> id) -> get();

            return $this -> response([
                "status" => "success",
                "rooms" => $rooms,
            ]);

        }

        $rooms = Rooms::with([
                            "mainClass" => ["ItsClass"]
                        ])
                    -> where("school_id", Auth::guard("schoolApi") -> user() -> id)
                    -> get();

        return $this -> response([
            "status" => "success",
            "rooms" => $rooms
        ]);

    }

    public function store(Request $request) {

        $rules = [
            'name' => 'required|string|max:50',
            'class_id' => [
                'required',
                Rule::exists('classes', 'id') -> where(function (Builder $query) {
                    return $query -> where('school_id', Auth::guard("schoolApi") -> user() -> id);
                }),
            ],
        ];
        $request['school_id'] = Auth::guard("schoolApi") -> user() -> id;

        $valid = $this -> valid($request -> all(), $rules);
        if ($valid == "true") {

            $room = Rooms::create($request -> all());
            $room -> load([
                "mainClass" => ["ItsClass"]
            ]);

            return $this -> response([
                'status' => 'success',
                'message' => 'Room added successfully',
                'room' => $room
            ]);

        } else {

            return $this -> response([
                'status' => 'error',
                'messages' => $valid
            ]);

        }

    }

    public function show($id) {

        $room = Rooms::where("id", $id) -> where('school_id', Auth::guard("schoolApi") -> user() -> id) -> get();
        $room -> load([
            'Periods' => [
                'Lessons' => [
                    'Teacher',
                    'Subject'
                ]
            ]
        ]);
        return $this -> response($room);

    }

    public function update(Request $request, Rooms $room) {
        
    }

    public function destroy($room_id) {

        $room = Rooms::where("id", $room_id) -> where("school_id", Auth::guard("schoolApi") -> user() -> id);
        $exist = $room -> get();

        if (count($exist) > 0) {

            $room -> delete();

            return $this -> response([
                "status" => "success",
                "message" => "Room deleted successfully"
            ]);
            
        } else {
            
            return $this -> response([
                "status" => "error",
                "message" => "Room not found"
            ]);

        }

    }

}
