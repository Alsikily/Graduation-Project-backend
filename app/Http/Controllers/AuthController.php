<?php

// Namespace
namespace App\Http\Controllers;

// Traits
use App\Traits\generalTrait;

// Classes
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

// Models
use App\Models\Student;
use App\Models\Parents;
use App\Models\Teacher;
use App\Models\School;

// Class
class AuthController extends Controller {

    use generalTrait;

    public function __construct() {

        $this -> middleware('authAll', ['except' => ['login','register']]);

    }

    public function login(Request $request) {

        $register_type = strtolower($request -> input("register_type"));
        $rules = [
            'email' => 'required|email|max:100|exists:' . $register_type . ',email',
            'password' => 'required|string|min:6',
        ];

        $valid = $this -> valid($request -> all(), $rules, [
            "email.exists" => "Invalid email or password",
        ]);
        $credentials = $request -> only('email', 'password');

        switch($valid) {

            case "true":

                switch($register_type) {

                    case "students":

                        $guard = 'studentApi';
                        break;

                    case "parents":

                        $guard = 'parentApi';
                        break;

                    case "schools":

                        $guard = 'schoolApi';
                        break;

                    case "teachers":

                        $guard = 'teacherApi';
                        break;

                }

                $token = Auth::guard($guard) -> attempt($credentials);
                if (!$token) {
                    return $this -> response([
                        'status' => 'error',
                        'messages' => ['email' => ['Invalid email or password']],
                    ]);
                }

                $user = Auth::guard($guard) -> user();
                return $this -> response([
                    'status' => 'success',
                    'message' => 'Loign successfully',
                    'user' => $user,
                    'authorisation' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ],
                    'loginType' => $register_type,
                ]);

                break;

            default:

                return $this -> response([
                    'status' => 'error',
                    'messages' => $valid
                ]);

                break;

        }

    }

    public function register(Request $request) {

        $register_type = strtolower($request -> input("register_type"));
        $rules = [
            'name' => 'required|max:100|string',
            // 'n_id' => 'required|string|max:14|min:14|unique:' . $register_type,
            'email' => 'required|email|max:100|unique:' . $register_type,
            'password' => 'required|min:6|string',
        ];
        $request['password'] = Hash::make($request -> input("password"));

        $valid = $this -> valid($request -> all(), $rules);

        switch($valid) {

            case "true":

                switch($register_type) {

                    case "students":

                        $user = Student::create($request -> all());
                        $guard = 'studentApi';
                        break;

                    case "parents":

                        $user = Parents::create($request -> all());
                        $guard = 'parentApi';
                        break;

                    case "schools":

                        $user = School::create($request -> all());
                        $guard = 'schoolApi';
                        break;

                    case "teachers":

                        $user = Teacher::create($request -> all());
                        $guard = 'teacherApi';
                        break;

                }

                $token = Auth::guard($guard) -> login($user);
                return $this -> response([
                    'status' => 'success',
                    'message' => 'Account created successfully',
                    'user' => $user,
                    'authorisation' => [
                        'token' => $token,
                        'type' => 'bearer',
                    ],
                    'loginType' => $register_type,
                ]);

                break;

            default:

                return $this -> response([
                    'status' => 'error',
                    'messages' => $valid
                ]);

                break;

        }

    }

    public function logout() {

        Auth::logout();
        return response() -> json([
            'status' => 'success',
            'message' => 'Successfully logged out',
        ]);

    }

}