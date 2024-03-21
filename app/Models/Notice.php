<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notice extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'notices';
    protected $fillable = ['title', 'description', 'main_image', 'other_images', 'publish', 'order'];

    public function services()
    {
        return $this->belongs(Service::class, 'service_id');
    }
}
