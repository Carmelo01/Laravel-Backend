<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RubricType extends Model
{
    use HasFactory;

    protected $table = 'rubric_types';

    public function category(){
        return $this->hasMany(Category::class);
    }
}
