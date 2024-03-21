<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Position extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table="positions";
    protected $fillable = ['title', 'short_title', 'slug', 'description', 'image', 'order', 'publish', 'sno'];

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_position_pivot', 'position_id', 'group_id');
    }

    public function services()
    {
        return $this->belongsToMany(Service::class, 'position_service_pivot', 'position_id', 'service_id');
    }

    public function levels()
    {
        return $this->belongsToMany(Level::class, 'position_level_pivot', 'position_id', 'level_id');
    }
}
