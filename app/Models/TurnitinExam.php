<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TurnitinExam extends Model
{
    use HasFactory;
    
    public $timestamps = false;
    public $table = 'exam_assignment_turnitin';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id'
    ];
}
