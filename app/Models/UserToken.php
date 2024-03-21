<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserToken extends Model
{
    use HasFactory;

    protected $table = 'user_tokens';
    protected $fillable = ['user_id', 'authorization_token'];
}
