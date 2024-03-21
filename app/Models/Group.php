<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Group extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $table="groups";
    protected $fillable = ['title', 'short_title', 'slug', 'description', 'image', 'parent_id', 'order', 'publish', 'sno'];

    public function groups()
    {
        return $this->belongsTo(Group::class, 'parent_id');
    }

    public function levels()
    {
        return $this->belongsToMany(Level::class, 'group_level_pivot', 'group_id', 'level_id');
    }

    public function positions()
    {
        return $this->belongsToMany(Position::class, 'group_position_pivot', 'group_id', 'position_id');
    }
}
