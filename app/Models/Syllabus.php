<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Syllabus extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'syllabuses';
    protected $fillable = ['title', 'description', 'image', 'publish', 'order', 'position_id', 'group_id', 'level_id'];

    public static function boot()
    {
        static::saving(function($model)
        {
            $position_id = request()->get('positions')?array_map('intval', request()->get('positions')):null;
            $group_id = request()->get('groups')?array_map('intval', request()->get('groups')):null;
            $level_id = request()->get('levels')?array_map('intval', request()->get('levels')):null;
            $service_id = request()->get('services')?array_map('intval', request()->get('services')):null;
            $syllabusExist = ServiceLevelGroupPositionPivot::where('syllabus_id', $model->id)->first();
            if(ServiceLevelGroupPositionPivot::where('syllabus_id', $model->id)->first())
            {
                $services = $syllabusExist->service_id;
                // dd($services);
                // dd($service_id);
                $service_id = array_map('intval', $service_id);
                // dd(json_encode($service_id));
                
                $syllabusExist->update([
                    'service_id' => json_encode($service_id),
                    'level_id' => json_encode($level_id), 
                    'group_id' => json_encode($group_id),
                    'position_id' => json_encode($position_id)
                ]);
            }
            else 
            {
                ServiceLevelGroupPositionPivot::create([
                    'syllabus_id'  => $model->id,
                    'service_id' => json_encode($service_id),
                    'level_id' => json_encode($level_id), 
                    'group_id' => json_encode($group_id),
                    'position_id' => json_encode($position_id)
                ]);
            }
            
        });
        parent::boot();
    }

    // public function services()
    // {
    //     $services = $this->service()->pluck('service_id')->toArray();
    //     // dd(gettype($services[0]));
    //     $service_id = json_decode($services[0], true);
    //     // $service_detail = Service::whereIn('id', $service_id)->get();
    //     return $this->belongsToMany(Service::class, 'service_level_group_position_syllabus_pivot', 'syllabus_id', 'service_id')->whereIn('service_id', $service_id);
    // }

    public function position()
    {
        return $this->belongsTo(Syllabus::class, 'position_id');
    }

    public function serviceLevelGroupPosition()
    {
        return $this->hasMany(ServiceLevelGroupPositionPivot::class, 'syllabus_id', 'id');
    }
}
