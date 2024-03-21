<?php

namespace App\Http\Controllers\Importers;

use App\Models\Category;
use Maatwebsite\Excel\Concerns\ToModel;

class CategoryImport implements ToModel
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
        $existing_datas = Category::select('sno')->pluck('sno')->toArray();
        $input = [];
        if($rowData['ParentCategory'])
        {
            $input['parent_id'] = Category::where('title', 'LIKE', '%'.$rowData['ParentCategory'].'%')->pluck('id')->first();
        }
        else 
        {
            $input['parent_id'] = null;
        }
        $input['title'] = $rowData['Title'];
        $input['short_title'] = $rowData['ShortTitle'];
        $input['description'] = $rowData['Description'];
        $input['order'] = (int) $rowData['Order'];
        if($rowData['Order'] = 'Yes')
        {
            $input['publish'] = true;
        }
        else 
        {
            $input['publish'] = false;
        }
        $input['image'] = $rowData['ImageLink'];
        $input['sno'] = (int) $rowData['S.No.'];
        if($existing_datas && $input['sno'])
        {
            if(in_array($input['sno'], $existing_datas))
            {
                $category_to_update = Category::where('sno', $input['sno'])->first();
                $category_to_update->update($input);
                return $category_to_update;
                
            }
        }
        return new Category([
            'parent_id' => $input['parent_id'],
            'title' => $input['title'], // Adjust the key based on the actual column name in your Excel file
            'short_title' => $input['short_title'], // Adjust the key based on the actual column name in your Excel file
            'description' => $input['description'],
            'order' => $input['order'],
            'publish' => $input['publish'],
            'image' => $input['image'],
            'sno' => $input['sno']
            // Add other fields as needed
        ]);
    }
}