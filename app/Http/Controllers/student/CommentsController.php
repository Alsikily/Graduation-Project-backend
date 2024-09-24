<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Query\Builder;

// Traits
use App\Traits\generalTrait;

// Models
use App\Models\Comments;

class CommentsController extends Controller {

    use generalTrait;

    public function index() {
        

    }

    public function store(Request $request) {

        $rules = [
            "body" => "required|string",
            "post_id" => "required|exists:posts,id",
        ];
        $request['student_id'] = Auth::guard("studentApi") -> user() -> id;

        $valid = $this -> valid($request -> all(), $rules);

        if ($valid == "true") {

            $comment = Comments::create($request -> all());
            $comment -> load([
                "student"
            ]);

            return $this -> response([
                "status" => "success",
                "comment" => $comment
            ]);

        }

        return $this -> response([
            "status" => "error",
            "messages" => $valid
        ]);

    }

    public function show(Comments $comments)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comments  $comments
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comments $comments)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comments  $comments
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comments $comments)
    {
        //
    }
}
