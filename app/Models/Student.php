<?php

namespace App\Models;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;

// Models
use App\Models\Lessons;
use App\Models\StudentsExams;
use App\Models\Result;
use App\Models\ResultStudent;
use App\Models\TeacherAbsences;
use App\Models\Parents;

class Student extends Authenticatable implements JWTSubject {
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'photo',
        'address',
        'gender',
        'rank',
        'school_id',
        'parent_id',
        'class_id',
        'n_id'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

     /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    public function subjects() {

        return $this -> hasMany(Lessons::class, "room_id", "room_id")
                    -> whereNotNull("subject_id");

    }

    public function getPhotoAttribute($value) {

        if ($value != null) {

            return asset("storage/files/studentUploads/" . $value);

        } else {

            return asset("images/avatar.png");

        }

    }

    public function getExamsSumExamDegreeAttribute($value) {

        if ($value != null) {

            return round($value, 2);

        } else {

            return 0;

        }

    }

    public function getExamsSumStudentDegreeAttribute($value) {

        if ($value != null) {

            return round($value, 2);

        } else {

            return 0;

        }

    }

    public function exams() {

        return $this -> hasMany(StudentsExams::class, "student_id", "id");

    }

    public function degrees() {

        return $this -> hasMany(ResultStudent::class, "student_id", "id");

    }

    public function result() {

        return $this -> belongsToMany(Result::class, "result_students", "student_id", "result_id");

    }

    public function absences() {

        return $this -> hasMany(TeacherAbsences::class, "student_id", "id");

    }

    public function parents() {

        return $this -> belongsTo(Parents::class, "parent_id", "id");

    }

}
