<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserPreference extends Model
{
    use HasFactory;

    protected $table = 'user_preferences';
    protected $fillable = ['user_id', 'service_id', 'level_id', 'group_id', 'position_id'];

    public function services()
    {
        return $this->belongsToMany(Service::class, 'service_id');
    }

    public function levels()
    {
        return $this->belongsToMany(Level::class, 'level_id');
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class, 'group_id');
    }

    public function positions()
    {
        return $this->belongsToMany(Position::class, 'position_id');
    }
}
