<?php

namespace App\Http\Controllers\Importers;

use App\Models\Category;
use App\Models\CategoryServicePivot;
use App\Models\Group;
use App\Models\GroupLevel;
use App\Models\Level;
use App\Models\Service;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;

class GroupImport implements ToModel
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
        if($rowData['ParentGroup'])
        {
            $input['parent_id'] = Group::where('title', 'LIKE', '%'.$rowData['ParentGroup'].'%')->pluck('id')->first();
        }
        else 
        {
            $input['parent_id'] = null;
        }
        $input = [
            'title' => $rowData['Title'],
            'short_title' => $rowData['ShortTitle'],
            'description' => $rowData['Description'],
            'slug' => $rowData['Slug'],
            'order' => (int) $rowData['Order'],
            'publish' => (Str::lower($rowData['Publish']) === 'yes')?true:false, // Convert to boolean
            'image' => $rowData['ImageLink'],
            'sno' => (int) $rowData['S.No.'],
            'parent_id' => $input['parent_id']
        ];
        // Check if the service with the same 'sno' exists
        $existingGroup = Group::where('sno', $input['sno'])->first();
        if ($existingGroup) {
            // Update existing service
            $existingGroup->update($input);
        } else {
            // Create new service
            $group = Group::create($input);
            $existingGroup = $group;
        }
        // Process categories
        $group_ids = Group::where('title', 'LIKE', '%' . $rowData['Title'] . '%')->first()->levels;
        $GroId = Group::where('title', 'LIKE', '%' . $rowData['Title'] . '%')->first();
        if (!empty($rowData['Levels'])) {
            $levels = explode(',', $rowData['Levels']);
            $levelsIds = [];
            foreach ($levels as $lev) {
                $level = trim($lev);
                $levelModel = Level::where('title', 'LIKE', '%' . $level . '%')->first();
                if ($levelModel) {
                    $levelsIds[] = $levelModel->id;
                }
            }
            if($group_ids && $group_ids->isNotEmpty())
            {
                // dd($group_ids);
                foreach($group_ids as $group_id)
                {
                    $group = $group_id->id;
                    if(in_array($group, $levelsIds))
                    {
                        $existingGroup->levels()->sync($levelsIds);
                    }
                    else 
                    {
                        $toDelete = explode(',', $level);
                        $existingGroup->levels()->detach($toDelete);
                        GroupLevel::whereIn('level_id', $toDelete)->delete();
                    }
                }
            }
            else 
            {
                $existingGroup->levels()->sync($levelsIds);
            }
            
            // Attach categories to the service
        }
        else 
        {
            $existingGroup->levels()->detach($GroId->id);
            $toDelete = explode(',', $GroId->id);
            GroupLevel::whereIn('group_id', $toDelete)->delete();
        }

        return $existingGroup;
    }
}
