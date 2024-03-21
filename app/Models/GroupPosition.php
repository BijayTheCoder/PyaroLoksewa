<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GroupPosition extends Model
{
    use HasFactory;
    protected $table="group_position_pivot";
    protected $fillable=['group_id', 'position_id'];
}
