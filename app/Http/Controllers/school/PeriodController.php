<?php

namespace App\Http\Controllers\school;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;

// Traits
use App\Traits\generalTrait;

// Models
use App\Models\Periods;
use App\Models\Lessons;
use App\Models\Days;

class PeriodController extends Controller {

    use generalTrait;

    public function index() {
        //
    }

    public function store(Request $request) {

        $rules = [
            'period' => 'required|max:50|string',
            'room_id' => [
                'required',
                Rule::exists('rooms', 'id') -> where(function (Builder $query) {
                    return $query -> where('school_id', Auth::guard("schoolApi") -> user() -> id);
                }),
            ]
        ];

        $valid = $this -> valid($request -> all(), $rules);

        if ($valid == "true") {

            $period = Periods::create($request -> all());
            $days = Days::select(["id", "day"]) -> get();

            foreach($days as $day) {

                Lessons::create([
                    "period_id" => $period -> id,
                    "day_id" => $day -> id,
                    "room_id" => $period -> room_id
                ]);

                // return "d";

            }

            return $this -> response([
                'status' => 'success',
                'message' => 'Period added successfully',
                'period' => $period,
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

    public function destroy($id) {
        //
    }

}
