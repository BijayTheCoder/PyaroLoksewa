<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Level extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table="levels";
    protected $fillable = ['title', 'short_title', 'slug', 'description', 'image', 'parent_id', 'order', 'publish', 'sno'];

    public function levels()
    {
        return $this->belongsTo(Level::class, 'parent_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_level_pivot', 'level_id', 'service_id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_level_pivot', 'level_id', 'group_id');
    }
}
