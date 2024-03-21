<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SyllabusPart extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'syllabus_parts';
    protected $fillable = ['papers_id', 'title', 'marks', 'percentage'];
}
