<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PositionLevel extends Model
{
    use HasFactory;
    protected $table="position_level_pivot";
    protected $fillable=['position_id', 'level_id'];
}
