<?php

namespace App\Http\Controllers\Importers;

use App\Models\Category;
use App\Models\CategoryServicePivot;
use App\Models\Group;
use App\Models\GroupLevel;
use App\Models\GroupPosition;
use App\Models\Level;
use App\Models\Position;
use App\Models\PositionLevel;
use App\Models\Service;
use App\Models\ServiceLevel;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;

class PositionImport implements ToModel
{
    private $headerRow = null;

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        // If header row is not set, assume this is the header row
        if ($this->headerRow === null) {
            $this->headerRow = $row;

            // Return null for the header row
            return null;
        }

        // Combine header row keys with the current row values
        $rowData = array_combine($this->headerRow, $row);

        // Extract and sanitize input data
        $input = [
            'title' => $rowData['Title'],
            'short_title' => $rowData['ShortTitle'],
            'description' => $rowData['Description'],
            'slug' => $rowData['Slug'],
            'order' => (int) $rowData['Order'],
            'publish' => (Str::lower($rowData['Publish']) === 'yes')?true:false, // Convert to boolean
            'image' => $rowData['ImageLink'],
            'sno' => (int) $rowData['S.No.'],
        ];
        // Check if the service with the same 'sno' exists
        $existingPosition = Position::where('sno', $input['sno'])->first();
        if ($existingPosition) {
            // Update existing service
            $existingPosition->update($input);
        } else {
            // Create new service
            $position = Position::create($input);
            $existingPosition = $position;
        }
        // Process categories
        $position_ids = Position::where('title', 'LIKE', '%' . $rowData['Title'] . '%')->first()->groups;
        $posId = Position::where('title', 'LIKE', '%' . $rowData['Title'] . '%')->first();
        if (!empty($rowData['Group'])) {
            $groups = explode(',', $rowData['Group']);
            $groupIds = [];
            foreach ($groups as $grou) {
                $group = trim($grou);
                $groupModel = Group::where('title', $group)->first();
                if ($groupModel) {
                    $groupIds[] = $groupModel->id;
                }
                // dd($level_ids);
            }
            if($position_ids && $position_ids->isNotEmpty())
            {
                foreach($position_ids as $position_id)
                {
                    $group = $position_id->id;
                    // dd($serviceIds);
                    if(in_array($group, $groupIds))
                    {
                        $existingPosition->groups()->sync($groupIds);
                    }
                    else 
                    {
                        $toDelete = explode(',', $group);
                        $existingPosition->groups()->detach($toDelete);
                        GroupPosition::whereIn('group_id', $toDelete)->delete();
                    }
                }
            }
            else 
            {
                $existingPosition->groups()->sync($groupIds);
            }
            
            // Attach categories to the service
        }
        else 
        {
            $existingPosition->groups()->detach($posId->id);
            $toDelete = explode(',', $posId->id);
            GroupPosition::whereIn('group_id', $toDelete)->delete();
        }

        $level_ids = Position::where('title', 'LIKE', '%' . $rowData['Title'] . '%')->first()->levels;

        if (!empty($rowData['Level'])) {
            $levels = explode(',', $rowData['Level']);
            $levelIds = [];
            foreach ($levels as $lev) {
                $level = trim($lev);
                $levelModel = Level::where('title', 'LIKE', '%' . $level . '%')->first();
                if ($levelModel) {
                    $levelIds[] = $levelModel->id;
                }
                // dd($level_ids);
            }
            if($level_ids && $level_ids->isNotEmpty())
            {
                foreach($level_ids as $level_id)
                {
                    $level = $level_id->id;
                    // dd($serviceIds);
                    if(in_array($level, $levelIds))
                    {
                        $existingPosition->levels()->sync($levelIds);
                    }
                    else 
                    {
                        $toDelete = explode(',', $level);
                        $existingPosition->groups()->detach($toDelete);
                        PositionLevel::whereIn('level_id', $level)->delete();
                    }
                }
            }
            else 
            {
                $existingPosition->levels()->sync($levelIds);
            }
            
            // Attach categories to the service
        }
        else 
        {
            $existingPosition->levels()->detach($posId->id);
            $toDelete = explode(',', $posId->id);
            PositionLevel::whereIn('level_id', $toDelete)->delete();
        }

        return $existingPosition;
    }
}
