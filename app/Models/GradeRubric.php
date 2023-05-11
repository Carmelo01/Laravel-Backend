<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class GradeRubric extends Model
{
    use HasFactory;

    protected $table = 'grade_rubrics';
    protected $fillable = [
        'grade',
        'rubrics_id',
        'faculty_id',
        'capsule_id',
    ];

    public function faculty(){
        return $this->belongsTo(User::class, 'faculty_id', 'id');
    }
    public function capsule(){
        return $this->belongsTo(Capsule::class, 'capsule_id', 'id');
    }
}
