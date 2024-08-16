<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StudentAuthResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request)
    {
        return ['id'=>$this->id,
        'id'=>$this->id,
        'name'=>$this->name,
        'email'=>$this->email,
        'city'=>$this->city,

        ];
    }
}
