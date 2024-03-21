<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Chapter extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'chapters';
    protected $fillable = ['title', 'syllabus_parts_id', 'serial_no', 'marks', 'percentage', 'parent_id'];
}
