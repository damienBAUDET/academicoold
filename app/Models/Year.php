<?php

namespace App\Models;

use App\Models\Period;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\app\Models\Traits\CrudTrait;

class Year extends Model
{
    use CrudTrait;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */
    // protected $primaryKey = 'id';
    public $timestamps = false;
    protected $guarded = ['id'];
    //protected $fillable = [];
    // protected $hidden = [];
    // protected $dates = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function periods()
    {
        return $this->hasMany(Period::class);
    }

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    public function getYearDistinctStudentsCountAttribute()
    {
        return DB::table('enrollments')
            ->join('courses', 'enrollments.course_id', 'courses.id')
            ->join('periods', 'courses.period_id', 'periods.id')
            ->where('periods.year_id', $this->id)
            ->whereIn('enrollments.status_id', ['1', '2']) // filter out cancelled enrollments, todo make this configurable.
            ->where('enrollments.parent_id', null)
            ->where('enrollments.deleted_at', null)
            ->distinct('student_id')
            ->count('enrollments.student_id');
    }

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
