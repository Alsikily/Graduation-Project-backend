<?php

namespace App\Http\Controllers\student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

// Traits
use App\Traits\generalTrait;

// Models
use App\Models\Book;
use App\Models\BookRates;

class StudentBooksController extends Controller {

    use generalTrait;

    public function index(Request $request) {

        $books = Book::with(["teacher" => function($query) {
                            $query -> select("id", "name");
                        }])
                        -> withSum("rates", "rate")
                        -> withCount("rates")
                        -> get();

        if (count($books) > 0) {

            foreach ($books as $book) {

                $studentRate = BookRates::where("student_id", Auth::guard("studentApi") -> user() -> id)
                                            -> where("book_id", $book -> id)
                                            -> first();

                $addRate = $studentRate != null ? $studentRate -> rate : 0;
                $book["studentRate"] = $addRate;

            }

        }

        return $this -> response([
            "status" => "success",
            "books" => $books
        ]);

    }

    public function store(Request $request) {
        
        $BookRate = BookRates::where("book_id", $request -> book_id)
                            -> where("student_id", Auth::guard("studentApi") -> user() -> id);

        $exist = $BookRate -> first();

        if ($exist != null) {

            $BookRate -> update([
                "rate" => $request -> rate
            ]);

        } else {

            BookRates::create([
                "book_id" => $request -> book_id,
                "student_id" => Auth::guard("studentApi") -> user() -> id,
                "rate" => $request -> rate
            ]);

        }

        return $this -> response([
            "status" => "success",
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
