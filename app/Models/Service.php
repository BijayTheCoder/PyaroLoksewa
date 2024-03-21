<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table="services";
    protected $fillable = ['title', 'short_title', 'slug', 'description', 'image', 'order', 'publish', 'sno'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_service_pivot', 'service_id', 'category_id');
    }

    public function levels()
    {
        return $this->belongsToMany(Level::class, 'service_level_pivot', 'service_id', 'level_id');
    }

    public function syllabuses()
    {
        return $this->belongsToMany(Syllabus::class, 'service_level_group_position_syllabus_pivot', 'service_id', 'syllabus_id');
    }
}
