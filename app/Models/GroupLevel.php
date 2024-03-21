<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupLevel extends Model
{
    use HasFactory;
    protected $table="group_level_pivot";
    protected $fillable=['group_id', 'level_id'];
}
