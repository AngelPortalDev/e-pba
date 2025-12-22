<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Facades\{Auth, Redirect, DB};
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentAtheDocument extends Model
{
    // use HasFactory, SoftDeletes;
    // public $timestamps = false;
    public $table = 'document_verification';
    protected $primaryKey = 'id';
    protected $guarded  = [];


    public function StudentDocsInfo()
    {
        return $this->hasOne(StudentDocument::class, 'student_id');
    }

}