<?php

namespace App\Http\Controllers\Importers;

use App\Models\Category;
use App\Models\CategoryServicePivot;
use App\Models\Service;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;

class ServiceImport implements ToModel
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
        $existingService = Service::where('sno', $input['sno'])->first();
        if ($existingService) {
            // Update existing service
            $existingService->update($input);
        } else {
            // Create new service
            $service = Service::create($input);
            $existingService = $service;
        }

        // Process categories
        if (!empty($rowData['Category'])) {
            $categories = explode(',', $rowData['Category']);
            $categoryIds = [];

            foreach ($categories as $category) {
                $category = trim($category);
                $categoryModel = Category::where('title', 'LIKE', '%' . $category . '%')->first();

                if ($categoryModel) {
                    $categoryIds[] = $categoryModel->id;
                }
            }

            // Attach categories to the service
            $existingService->categories()->sync($categoryIds);
        }

        return $existingService;
    }
}
