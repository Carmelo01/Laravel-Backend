<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CommentCapsule extends Model
{
    use HasFactory;

    protected $table = 'comment_capsules';
    protected $fillable = [
        // 'faculty_id',
        'capsule_id',
        'comment'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'faculty_id', 'id');
    }

    public function capsule(){
        return $this->belongsTo(Capsule::class);
    }

    public function admin(){
        return $this->belongsTo(Admin::class);
    }

}
