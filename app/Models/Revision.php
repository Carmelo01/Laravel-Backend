<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Revision extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'revisions';
    protected $fillable = [
        'title',
        'file_location',
        'capsule_id',
        'comment',
    ];

    //Testing lang

    public function capsule(){
        return $this->belongsTo(Capsule::class, 'capsule_id', 'id');
    }
}
