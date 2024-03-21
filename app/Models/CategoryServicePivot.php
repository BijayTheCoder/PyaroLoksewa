<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoryServicePivot extends Model
{
    use HasFactory;
    protected $table = 'category_service_pivot';
    protected $fillable = ['category_id', 'service_id'];
}
