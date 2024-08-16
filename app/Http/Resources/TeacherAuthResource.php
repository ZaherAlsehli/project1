<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TeacherAuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return[
            'id' => $this->id,
             'user_id' => $this->user_id,
            'cv_path' => $this->cv_path,
            'category_id' => new CategoryResource($this->whenLoaded('category')),
       
          ] ;   
    }
}
