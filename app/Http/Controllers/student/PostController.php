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
use App\Models\Post;

class PostController extends Controller {

    use generalTrait;

    public function index() {

        $posts = Post::with([
                            "comments" => [
                                "student"
                            ],
                            "student"
                        ])
                    -> withCount("comments")
                    -> get();

        return $this -> response([
            "status" => "success",
            "posts" => $posts
        ]);

    }

    public function store(Request $request) {

        $rules = [
            "body" => "required|string",
        ];
        $request['student_id'] = Auth::guard("studentApi") -> user() -> id;

        $valid = $this -> valid($request -> all(), $rules);

        if ($valid == "true") {

            $post = Post::create($request -> all());
            $post -> load([
                "comments" => [
                    "student"
                ],
                "student"
            ]);
            $post -> loadCount("comments");

            return $this -> response([
                "status" => "success",
                "message" => "Posted Successfully",
                "post" => $post
            ]);

        }

        return $this -> response([
            "status" => "error",
            "messages" => $valid
        ]);

    }

    public function show(Post $post) {
        //
    }

    public function update(Request $request, Post $post) {
        //
    }

    public function destroy(Post $post) {
        //
    }

}
