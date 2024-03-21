<?php

namespace App\Http\Controllers\Admin;

use App\Casts\LevelData;
use App\Http\Controllers\Controller;
use App\Models\Group;
use App\Models\Level;
use App\Models\Position;
use App\Models\Service;
use App\Models\ServiceLevelGroupPositionPivot;
use App\Models\ServiceLevelPivot;
use Illuminate\Http\Request;

class LevelByCategoryController extends Controller
{
    protected $levelData;

    public function __construct(LevelData $levelData)
    {
        $this->levelData = $levelData;
    }

    public function get_level(Request $request)
    {
        if ($request->services)
        {
            $html = '';
            $var = '';
            $service_ids = $request->services;
            $levels = Level::whereHas('services', function($query) use ($service_ids){
                $query->whereIn('service_id', $service_ids);
            })->get();
            // $levels = Level::whereIn('id', $level_ids)->get();
            $service_ids = json_encode(array_map('intval', $service_ids));
            $services_exist_levels_ids = ServiceLevelGroupPositionPivot::where('service_id', $service_ids)->pluck('level_id')->toArray();
            $convertedArray = array_map('intval', json_decode($services_exist_levels_ids[0], true));
            // $services_exist_levels = Level::whereIn('id', array_keys($level_ids))->get();
            // dd($services_exist_levels);
            foreach ($levels as $level) {
                // $isChecked = !empty($position->id) == $positionContent->position_id) checked="checked";
                // foreach ($services_exist_levels as $p) {
                    if(in_array($level->id, $convertedArray)) { $var = ' checked ';
                    $level_existed [] = $level->id;}
                    else 
                    {
                        $var = '';
                        $level_existed = [];
                    }
                // }
                $html .= ' <input class="loadGroups" data-initialids="'. json_encode($level_existed) .'" type="checkbox" name="levels[]" value="' . $level->id . '" onclick="loadGroups(this) "' . $var . '>' . ' '.$level->title.'<br\>';
                // foreach($level->groups as $group)
                // {
                //     $html .= ' <input type="checkbox" name="groups[]" value="' . $level->id . '" onclick="loadPositions(' . $level->id . ') "' . $var . '>' . ' '.$level->title.'<br\>';
                // }
            }
            return response()->json(['html' => $html, 'levelId' => $convertedArray, 'var' => $var]);
        }
        elseif ($request->service_id) {
            $html = '';
            $var = '';
            $ids = explode(',', $request->service_id);
            $levels = Level::whereHas('services', function($query) use ($ids){
                $query->whereIn('service_id', $ids);
            })->get();
            // $levels = Level::whereIn('id', $level_ids)->get();
            $exist_levels_ids = ServiceLevelGroupPositionPivot::where('syllabus_id', $request->syllabus_id)->pluck('level_id')->toArray();
            $convertedArray = array_map('intval', json_decode($exist_levels_ids[0], true));
            // $exist_levels = Level::whereIn('id', $exist_levels_ids)->get();
            foreach ($levels as $level) {
                // $isChecked = !empty($position->id) == $positionContent->position_id) checked="checked";
                    if(in_array($level->id, $convertedArray)) { $var = ' checked ';
                    $level_existed [] = $level->id;}
                    else 
                    {
                        $level_existed = [];
                        $var = '';
                    }
                $html .= ' <input class="loadGroups" type="checkbox" data-initialids="'. json_encode($level_existed) .'" name="levels[]" value="' . $level->id . '" onclick="loadGroups(this) "' . $var . '>' . ' '.$level->title;
            }
            return response()->json(['html' => $html, 'levelId' => $convertedArray, 'var' => $var]);
        }
        else 
        {
            $html = '';
            return response()->json(['html' => $html, 'levelId' => [], 'var' => '']);
        }
    }

    public function get_group(Request $request)
    {
        if ($request->level_id_default) {
            $initial_ids = $request->level_id_default;
            $html = '';
            $var = '';
            if (gettype($request->level_id_default) != 'array') {
                $ids = explode(',', $request->level_id_default);
            } else {
                $ids = $request->level_id_default;
            } 
            $groups = Group::whereHas('levels', function($query) use ($ids, $initial_ids, $request){
                if($request->checked != 'false')
                {
                    $query->whereIn('level_id', $initial_ids)->whereIn('level_id', $ids);
                }
                else 
                {   
                    $query->whereIn('level_id', $initial_ids)->whereNotIn('level_id', $ids);
                }            
            })->get();
            // $levels = Level::whereIn('id', $level_ids)->get();
            $exist_groups_ids = ServiceLevelGroupPositionPivot::where('syllabus_id', $request->syllabus_id)->pluck('group_id')->toArray();
            $convertedArray = array_map('intval', json_decode($exist_groups_ids[0], true));
            foreach ($groups as $group) {
                // Check if the group exists in the existing group IDs array
                if (in_array($group->id, $convertedArray)) {
                    $var = ' checked ';
                    $group_existed [] = $group->id;
                } else {
                    $group_existed = [];
                    $var = '';
                }
                // Build HTML for the checkbox based on the checked status
                $html .= '<input class="loadPositions" type="checkbox" data-initialids="'. json_encode($group_existed) .'" name="groups[]" value="' . $group->id . '" onclick="loadPositions(this)"' . $var . '>' . ' ' . $group->title;
            }
            // dd($html);
            return response()->json(['html' => $html, 'groupId' => $convertedArray, 'var' => $var, 'initial_ids' => $initial_ids]);
        }
        elseif ($request->level_id) {
            $initial_ids = $request->checked_ids;
            $html = '';
            $var = '';
            
                if (gettype($request->level_id) != 'array') {
                    $ids = explode(',', $request->level_id);
                } else {
                    $ids = $request->level_id;
                }
                if($initial_ids)
                { 
                    $groups = Group::whereHas('levels', function($query) use ($ids, $initial_ids, $request){
                        if($request->checked == "false")
                        {
                            $query->whereIn('level_id', $initial_ids);
                        }
                        else 
                        {
                            array_push($initial_ids, $request->level_id);
                            // dd($initial_ids); 
                            $query->whereIn('level_id', $initial_ids);
                        }
                    })->get();
                }
                else 
                {
                    $groups = Group::whereHas('levels', function($query) use ($ids, $initial_ids, $request){
                        if($request->checked != "false")
                        {
                            $query->whereIn('level_id', $ids);
                        }
                        else 
                        {
                            $query->whereIn('level_id', $ids)->whereNotIn('level_id', $ids);
                        }
                    })->get();
                }
                // $levels = Level::whereIn('id', $level_ids)->get();
                $exist_groups_ids = ServiceLevelGroupPositionPivot::where('syllabus_id', $request->syllabus_id)->pluck('group_id')->toArray();
                $convertedArray = array_map('intval', json_decode($exist_groups_ids[0], true));
                foreach ($groups as $group) {
                    // Check if the group exists in the existing group IDs array
                    if (in_array($group->id, $convertedArray)) {
                        $var = ' checked ';
                        $group_existed [] = $group->id;
                    } else {
                        $var = '';
                        $group_existed = [];
                    }
        
                    // Build HTML for the checkbox based on the checked status
                    $html .= '<input class="loadPositions" type="checkbox" data-initialids="'. json_encode($group_existed) .'" name="groups[]" value="' . $group->id . '" onclick="loadPositions(this)"' . $var . '>' . ' ' . $group->title;
                }
                return response()->json(['html' => $html, 'groupId' => $convertedArray, 'var' => $var]);
            }
        else 
        {
            return response()->json(['html' => '']);
        }
    }

    public function get_position(Request $request)
    {
        if ($request->group_id_default) {
            $initial_group_ids = $request->group_id_default;
            $html = '';
            $var = '';
            if (gettype($request->group_id_default) != 'array') {
                $ids = explode(',', $request->group_id_default);
            } else {
                $ids = $request->group_id_default;
            } 
            $positions = Position::whereHas('groups', function($query) use ($ids, $initial_group_ids, $request){
                if($request->checked != 'false')
                {
                    $query->whereIn('group_id', $initial_group_ids)->whereIn('group_id', $ids);
                }
                else 
                {   
                    $query->whereIn('group_id', $initial_group_ids)->whereNotIn('group_id', $ids);
                }            
            })->get();
            // $levels = Level::whereIn('id', $level_ids)->get();
            $exist_positions_ids = ServiceLevelGroupPositionPivot::where('syllabus_id', $request->syllabus_id)->pluck('position_id')->toArray();
            $convertedArray = array_map('intval', json_decode($exist_positions_ids[0], true));
            
                foreach ($positions as $position) {
                    // Check if the group exists in the existing group IDs array
                    if (in_array($position->id, $convertedArray)) {
                        $var = ' checked ';
                        $position_existed [] = $position->id;
                    } else {
                        $var = '';
                        $position_existed = [];
                    }
        
                    // Build HTML for the checkbox based on the checked status
                    $html .= '<input type="checkbox" data-initialids="'. json_encode($position_existed) .'" name="positions[]" value="' . $position->id  . '" . ' .$var . '>' . ' ' . $position->title;
                }
            // $levels = Level::whereIn('id', $level_ids)->get();
            
            return response()->json(['html' => $html, 'positionId' => $convertedArray, 'var' => $var, 'initial_ids' => $initial_group_ids]);
        }
        elseif ($request->group_id) {
            $initial_group_ids = $request->checked_ids;
            $html = '';
            $var = '';
            
                if (gettype($request->group_id) != 'array') {
                    $ids = explode(',', $request->group_id);
                } else {
                    $ids = $request->group_id;
                } 

                if($initial_group_ids)
                {
                    $positions = Position::whereHas('groups', function($query) use ($ids, $initial_group_ids, $request){
                        if($request->checked == "false")
                        {
                            $query->whereIn('group_id', $initial_group_ids);
                        }
                        else 
                        {
                            array_push($initial_group_ids, $request->group_id);
                            $query->whereIn('group_id', $initial_group_ids);
                        }
                    })->get();
                }
                else 
                {
                    $positions = Position::whereHas('groups', function($query) use ($ids, $request){
                        if($request->checked != "false")
                        {
                            $query->whereIn('group_id', $ids);
                        }
                        else 
                        {
                            $query->whereIn('group_id', $ids)->whereNotIn('group_id', $ids);
                        }
                    })->get();
                }
                // $levels = Level::whereIn('id', $level_ids)->get();
                $exist_positions_ids = ServiceLevelGroupPositionPivot::where('syllabus_id', $request->syllabus_id)->pluck('position_id')->toArray();
                $convertedArray = array_map('intval', json_decode($exist_positions_ids[0], true));
                foreach ($positions as $position) {
                    // Check if the group exists in the existing group IDs array
                    if (in_array($position->id, $convertedArray)) {
                        $var = ' checked ';
                        $position_existed [] = $position->id;
                    } else {
                        $var = '';
                        $position_existed = [];
                    }
        
                    // Build HTML for the checkbox based on the checked status
                    $html .= '<input type="checkbox" data-initialids="'. json_encode($position_existed) .'" name="positions[]" value="' . $position->id  . '" . ' .$var . '>' . ' ' . $position->title;
                }
                return response()->json(['html' => $html, 'positionId' => $convertedArray, 'var' => $var]); 
        }
        else 
        {
            return response()->json(['html' => '']);
        }
    }
}
