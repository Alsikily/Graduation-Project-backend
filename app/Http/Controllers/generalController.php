<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

// Models
use App\Models\School;
use App\Models\Student;
use App\Models\Teachers;
use App\Models\Parents;
use App\Models\Classes;
use App\Models\Rooms;

class generalController extends Controller {
    
    public function factory() {

        $parents = Parents::factory() -> create();
        $schools = School::factory() -> has(Classes::factory() -> has(Rooms::factory())) -> has(Student::factory() -> for($parents)) -> count(3) -> create();
        // $schools = School::factory() -> has(Student::factory() -> has(Parents::factory() -> create())) -> count(3) -> create();

        return "Done successfully";

    }

}
