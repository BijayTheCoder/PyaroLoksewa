<?php

namespace App\Http\Resources;

use App\Models\Position;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // $positions = Position::where('publish', 1)->where('title', 'LIKE', '%'.'खरिदार वा सो सरह'.'%')->orWhere('title', 'LIKE', '%'.'नायव सुब्बा वा सो सरह'.'%')->get();
        return [
            'id' => $this->id,
            'title' => $this->title,
            'short_title' => $this->short_title,
            // 'major_positions' => ($positions->isNotEmpty()) ? $positions->map(function ($positions){
            //     if($positions) {
            //         return [
            //             'id' => $positions->id,
            //             'title' => $positions->title,
            //             'groups' => (($positions->groups)->isNotEmpty()) ? $positions->groups->map(function ($positiongroups){
            //                 if($positiongroups)
            //                 {
            //                     return [
            //                         'id' => $positiongroups->id,
            //                         'title' => $positiongroups->title
            //                     ];
            //                 }
            //             })->filter()->unique('id')->values():null,
            //         ];
            //     }
            // })->filter()->unique('id')->values():null,
            'levels' => (($this->levels)->isNotEmpty()) ? $this->levels->map(function ($levels) {
                if ($levels) {
                    return [
                        'id' => $levels->id,
                        'title' => $levels->title,
                        'groups' => (($levels->groups)->isNotEmpty()) ? $levels->groups->map(function ($groups){
                            if($groups) {
                                return [
                                    'id' => $groups->id,
                                    'title' => $groups->title,
                                    'sub_groups' => ($groups->groups)?$groups->groups:null,
                                    'positions' => (($groups->positions)->isNotEmpty()) ? $groups->positions->map(function ($grouppositions){
                                        if($grouppositions) {
                                            return [
                                                'id' => $grouppositions->id,
                                                'title' => $grouppositions->title
                                            ];
                                        }
                                    })->filter()->unique('id')->values():null,
                                ];
                            }
                        })->filter()->unique('id')->values():null,
                    ];
                } else {
                    return null;
                }
            })->filter()->unique('id')->values():null,
            'slug' => $this->slug,
            'description' => $this->description,
            'image' => $this->image,
            'order' => $this->order, 
            'publish' => $this->publish,
            'sno' => $this->sno
        ];
    }
}
