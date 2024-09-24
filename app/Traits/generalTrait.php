<?php

namespace App\Traits;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

trait generalTrait {

    // Response Data As JSON Object
    public function response($data) {

        return response() -> json($data);

    }

    // Validate Request
    public function valid($request, $rules, $customization = []) {

        $validated = Validator::make($request, $rules, $customization);

        if ($validated -> fails()) {

            return $validated -> errors();

        } else {

            return "true";

        }

    }

    public function deleteTeacherItem($model, $item_id, $itemName) {

        $item = $model::where("id", $item_id) -> where("teacher_id", Auth::guard("teacherApi") -> user() -> id);
        $exist = $item -> get();

        if (count($exist) > 0) {

            $item -> delete();

            return $this -> response([
                "status" => "success",
                "message" => "$itemName deleted successfully"
            ]);

        } else {

            return $this -> response([
                "status" => "error",
                "message" => "$itemName not found"
            ]);

        }

    }

}