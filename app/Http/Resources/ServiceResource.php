<?php

namespace App\Http\Resources;

use App\Services\FormRequestHandleInputs;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
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
            'category'=>CategoryResource::make($this->whenLoaded('category')),
            'category_id'=>$this->category_id,
            'images'=>ImagesResource::collection($this->whenLoaded('images')),
            'projects_count'=>$this->projects_count,
            'created_at'=>$this->created_at->format('Y h d,h:i A'),
        ];
    }
}
