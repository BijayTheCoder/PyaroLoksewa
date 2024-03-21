<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceLevelGroupPositionPivot extends Model
{
    use HasFactory;

    protected $table = 'service_level_group_position_syllabus_pivot';
    protected $fillable = ['syllabus_id', 'position_id', 'group_id', 'level_id', 'service_id'];

}
