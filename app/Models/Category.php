<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'categories';
    protected $fillable = [
        'title',
        'type_id'
    ];

    public function rubric(){
        return $this->hasMany(Rubric::class);
    }

    public function type(){
        return $this->belongsTo(RubricType::class, 'type_id', 'id');
    }

}
