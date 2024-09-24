<?php

namespace App\Http\Controllers\school;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;

// Models
use App\Models\Teacher;
use App\Models\SchoolAbsences;
use App\Models\SchoolTAbsence;

// Traits
use App\Traits\generalTrait;

class SchoolAbsence extends Controller {

    use generalTrait;

    public function index() {

        $teachers = Teacher::with(["absences" => function($query) {
                                    $query -> whereMonth("created_at", date("m")) -> orderBy("created_at");
                            }])
                            -> where('school_id', Auth::guard("schoolApi") -> user() -> id)
                            -> get();

        $absence = SchoolTAbsence::where("school_id", Auth::guard("schoolApi")-> user() -> id)
                                    -> whereMonth("created_at", date("m"))
                                    -> orderBy("created_at")
                                    -> get();

        return $this -> response([
            "status" => "success",
            "teachers" => $teachers,
            "absence" => $absence
        ]);

    }

    public function store(Request $request) {
        $rules = [
            'comes.*' => [
                Rule::exists('teachers', 'id') -> where(function (Builder $query) {
                    return $query -> where('school_id', Auth::guard("schoolApi") -> user() -> id);
                }),
                Rule::unique('school_absences', 'teacher_id') -> where(function (Builder $query) {
                    return $query -> whereDate("created_at", date("Y-m-d"));
                }),
            ],
        ];
        $valid = $this -> valid($request -> all(), $rules);
        if ($valid == "true") {
            $lastTakeAbsence = SchoolTAbsence::selectRaw("date(created_at) as date")
                                                -> where('school_id', Auth::guard("schoolApi") -> user() -> id)
                                                -> orderBy("created_at", "DESC")
                                                -> first();
            $allowed = !$lastTakeAbsence ? true : ($lastTakeAbsence["date"] != date("Y-m-d") ? true : false);
            if ($allowed) {
                $takeAbsence = SchoolTAbsence::create([
                    "school_id" => Auth::guard("schoolApi") -> user() -> id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $teachersAbsences = [];
                foreach($request -> teachers as $teacher_id) {
                    $teacherAbsence = SchoolAbsences::create([
                        "teacher_id" => $teacher_id,
                        "take_id" => $takeAbsence -> id,
                        "status" => "test",
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    $teachersAbsences[] = $teacherAbsence;
                }
                return $this -> response([
                    'status' => 'success',
                    "absence" => $takeAbsence,
                    "teachersAbsences" => $teachersAbsences,
                ]);
            } else {
                return $this -> response([ 'status' => 'error', 'message' => 'Absence has been taken today' ]);
            }
        }
        return $this -> response([ 'status' => 'error', 'messages' => $valid ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
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
