<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignedCapsule extends Model
{
    use HasFactory;
    //, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'assigned_capsules';
    protected $fillable = [
        'faculty_id',
        'capsule_id',
        'grade'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'faculty_id', 'id');
    }

    public function capsule(){
        return $this->belongsTo(Capsule::class);
    }
}
