<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table="categories";
    protected $fillable = ['parent_id', 'title', 'short_title', 'slug', 'description', 'image', 'order', 'publish', 'sno'];

    public function category()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'category_service_pivot', 'category_id', 'service_id');
    }
}
