<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class HomeworkAnswers extends Model
{
    use HasFactory;
    public $table = 'exam_homework_answers';
    protected $guarded  = [];
}