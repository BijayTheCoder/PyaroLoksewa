<?php

namespace App\Http\Controllers\Importers;

use App\Models\Category;
use App\Models\CategoryServicePivot;
use App\Models\Level;
use App\Models\Service;
use App\Models\ServiceLevel;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;

class LevelImport implements ToModel
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
        if($rowData['ParentLevel'])
        {
            $input['parent_id'] = Level::where('title', 'LIKE', '%'.$rowData['ParentLevel'].'%')->pluck('id')->first();
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
        $existingLevel = Level::where('sno', $input['sno'])->first();
        if ($existingLevel) {
            // Update existing service
            $existingLevel->update($input);
        } else {
            // Create new service
            $level = Level::create($input);
            $existingLevel = $level;
        }
        // Process categories
        $level_ids = Level::where('title', 'LIKE', '%' . $rowData['Title'] . '%')->first()->services;
        $levId = Level::where('title', 'LIKE', '%' . $rowData['Title'] . '%')->first();
        if (!empty($rowData['Service'])) {
            $services = explode(',', $rowData['Service']);
            $serviceIds = [];
            foreach ($services as $servi) {
                $service = trim($servi);
                $serviceModel = Service::where('title', 'LIKE', '%' . $service . '%')->first();
                if ($serviceModel) {
                    $serviceIds[] = $serviceModel->id;
                }
                // dd($level_ids);
            }
            if($level_ids && $level_ids->isNotEmpty())
            {
                foreach($level_ids as $level_id)
                {
                    $level = $level_id->id;
                    // dd($serviceIds);
                    if(in_array($level, $serviceIds))
                    {
                        $existingLevel->services()->sync($serviceIds);
                    }
                    else 
                    {
                        $toDelete = explode(',', $level);
                        $existingLevel->services()->detach($toDelete);
                        ServiceLevel::whereIn('level_id', $toDelete)->delete();
                    }
                }
            }
            else 
            {
                $existingLevel->services()->sync($serviceIds);
            }
            
            // Attach categories to the service
        }
        else 
        {
            $existingLevel->services()->detach($levId->id);
            $toDelete = explode(',', $levId->id);
            ServiceLevel::whereIn('level_id', $toDelete)->delete();
        }

        return $existingLevel;
    }
}
