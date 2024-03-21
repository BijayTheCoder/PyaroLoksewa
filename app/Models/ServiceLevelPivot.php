<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceLevelPivot extends Model
{
    use HasFactory;

    protected $table = 'service_level_pivot';
    protected $fillable = ['service_id', 'level_id'];  
}
