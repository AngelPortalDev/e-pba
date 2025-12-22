<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeworkQuestion extends Model
{
    use HasFactory, SoftDeletes;
    public $timestamps = false;
    public $table = 'exam_homework_questions';
    protected $guarded  = [];
    protected $visible  = ['id', 'homework_id', 'section_id','question','question_file_url','question_file_name','mimes','status','homeworkAnswers'];

    public function homeworkAnswers()
    {
        return $this->hasMany(HomeworkAnswers::class,  'question_id');
    }
}
