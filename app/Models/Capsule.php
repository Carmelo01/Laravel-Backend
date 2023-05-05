<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Capsule extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

    protected $dates = ['deleted_at'];
    protected $table = 'capsules';
    protected $fillable = [
        'author_id',
        'title',
        'file',
        'description',
        'status',
        'datePosted',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'author_id', 'id');
    }

    public function comment(){
        return $this->hasMany(CommentCapsule::class);
    }

    public function assigncapsule(){
        return $this->hasMany(AssignedCapsule::class);
    }

    public function revision(){
        return $this->hasMany(Revision::class);
    }

    public function graderubric(){
        return $this->hasMany(GradeRubric::class);
    }

}
