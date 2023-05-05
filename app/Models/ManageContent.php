<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManageContent extends Model
{
    use HasFactory;

    protected $table = 'manage_contents';
    protected $fillable = [
        'cictLogo',
        'sideNav',
    ];
}
