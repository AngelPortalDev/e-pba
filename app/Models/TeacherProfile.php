<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\{Auth, Redirect};

class TeacherProfile extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $table = 'lecturers_master';
    protected $primaryKey = 'id';
    protected $fillable = [
        'lactrure_name',
        'mobile',
        'email',
        'designation',
        'discription',
        'image',
        'created_by',
        'created_at',
        'updated_by',
        'updated_at'
    ];

    public function institute()
    {
        return $this->belongsTo(InstituteProfile::class, 'university_code', 'university_code');
    }

    public function getInstituteWiseTeacherList($where)
    {   
        $instituteData = [];
        if (Auth::check()) {
            $query = TeacherProfile::with([
                'institute' => function ($query) {
                    $query->with([
                        'user' => function ($userQuery) {
                            // $userQuery->where('status', '0');
                        }
                    ]);
                }
            ]);
            if (isset($where) && count($where) > 0 && is_array($where)) {
                $instituteData = $query->where($where)->whereNotNull('university_code')->orderBy('id','desc')->get();
            } else {
                $instituteData = $query->whereNotNull('university_code')->orderBy('id','desc')->get();
            }
            return $instituteData;
        }
    }
    
}