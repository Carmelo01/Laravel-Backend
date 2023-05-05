<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class AssignedRevision extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'assigned_revisions';
    protected $fillable = [
        'title',
        'datePosted',
        'file',
        'comment',
        'grade',
        'updateNo',
        'revisionId',
        'facultyId'
        
    ];
}
