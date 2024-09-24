<?php

namespace App\Http\Controllers\teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Smalot\PdfParser\Parser as PdfParser;

// Traits
use App\Traits\generalTrait;

// Models
use App\Models\Book;


class BooksController extends Controller {

    use generalTrait;

    public function index(Request $request) {

        $books = Book::where("teacher_id", Auth::guard("teacherApi") -> user() -> id)
                    -> with(["teacher"])
                    -> withSum("rates", "rate")
                    -> withCount("rates")
                    -> get();

        return $this -> response([
            "status" => "success",
            "books" => $books
        ]);

    }

    public function store(Request $request) {

        $requestData = $request -> all();
        $rules = [
            'name' => 'required|max:255|string',
            'description' => 'string',
            'image' => 'required|image|mimes:jpg,jpeg,png,bmp',
            'book' => 'required|mimes:pdf|max:10000',
        ];
        $requestData['teacher_id'] = Auth::guard("teacherApi") -> user() -> id;
        $requestData['subject_id'] = Auth::guard("teacherApi") -> user() -> subject_id;

        $valid = $this -> valid($requestData, $rules);

        if ($valid == "true") {

            $image = Storage::disk('teacherUploads') -> put('booksImages', $requestData['image']);
            $book = Storage::disk('teacherUploads') -> put('books', $requestData['book']);
            $requestData['image'] = $image;
            $requestData['book'] = $book;
            $requestData['size'] = ceil($request -> file("book") -> getSize() / 1024);

            $book = Book::create($requestData);
            $book -> load("teacher");
            $book -> loadSum("rates", "rate");
            $book -> loadCount("rates");

            return $this -> response([
                'status' => 'success',
                'message' => 'Book added successfully',
                'book' => $book,
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

    public function destroy($book_id) {

        return $this -> deleteTeacherItem(Book::class, $book_id, "Book"); 

    }

}
