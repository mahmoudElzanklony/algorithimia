<?php

namespace App\Http\Resources;

use App\Services\FormRequestHandleInputs;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
          'id'=>$this->id,
          'name'=>str_contains(request()->fullUrl(), 'dashboard') == false ? FormRequestHandleInputs::handle_output_column($this->name):$this->name,
          'info'=>str_contains(request()->fullUrl(), 'dashboard') == false ? FormRequestHandleInputs::handle_output_column($this->info):$this->info,
          'skills'=>$this->skills,
          'link'=>$this->link,
          'service'=>ServiceResource::make($this->whenLoaded('service')),
          'service_id'=>$this->service_id,
          'images'=>ImagesResource::collection($this->whenLoaded('images')),
          'created_at'=>$this->created_at->format('Y m d, h:i A'),

        ];
    }
}
