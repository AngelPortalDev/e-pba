<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\{Auth, Redirect, Validator, Storage, DB, Hash, Http};
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeworkModule extends Model
{
    use HasFactory,SoftDeletes;
    public $timestamps = false;
    public $table = 'exam_homework';
    protected $guarded  = [];
    // protected $visible  = ['id', 'homework_title', 'award_id',  'is_active', 'AwardCourse',  'mcqQuestion'];
    protected $visible  = ['id', 'homework_title','instructions','award_id','section_id','is_active','instrcution_file_url','instrcution_file_name', 'AwardCourse','homeworkQuestion','homeworkSection'];

    
    public function AwardCourse()
    {
        return $this->hasOne(CourseModule::class, 'id', 'award_id')->where('category_id', 1);
    }

    public function homeworkQuestion()
    {
        return $this->hasMany(HomeworkQuestion::class, 'homework_id')->where('is_deleted', 'No');
    }

    public function homeworkExam()
    {
        return $this->belongsTo(ExamManage::class, 'exam_id', 'id')->where('exam_type', '=', 10);
    }

    public function homeworkSection()
    {
        return $this->hasOne(SectionModel::class, 'id','section_id');
    }
    
    
    public function getHomeworkDetails($where = [], $select = [])
    {

        if (Auth::check()) {
            $query = $this->with([
                'homeworkSection:id,section_name',
                'AwardCourse:id,course_title',         // shorthand for the same closure
                'homeworkQuestion:id,question,homework_id',
            ])->where('is_deleted', 'No')->orderByDesc('id');
            $homeworkData = [];
            if (isset($where) && count($where) > 0 && is_array($where)) {
                $homeworkData = $query->where($where)->get()->toArray();
            } else {
                $homeworkData = $query->get()->toArray();
            }
            return $homeworkData;
        }
        return redirect('/login');
    }
}
