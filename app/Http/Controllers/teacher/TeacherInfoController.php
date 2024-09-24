<?php

namespace App\Http\Controllers\teacher;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

// Traits
use App\Traits\generalTrait;

// Models
use App\Models\Lessons;

class TeacherInfoController extends Controller {

    use generalTrait;

    public function rooms() {

        $rooms = Lessons::select(["room_id"])
                            -> with("room")
                            -> where("teacher_id", Auth::guard("teacherApi") -> user() -> id)
                            -> groupBy(DB::raw("room_id"))
                            -> orderBy("room_id")
                            -> get();

        return $this -> response([
            "status" => "success",
            "rooms" => $rooms
        ]);

    }

}
