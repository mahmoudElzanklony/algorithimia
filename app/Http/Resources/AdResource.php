<?php

namespace App\Http\Resources;

use App\Services\FormRequestHandleInputs;
use Illuminate\Http\Resources\Json\JsonResource;

class AdResource extends JsonResource
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
            'requirements'=>AdRequirmentResource::collection($this->whenLoaded('requirements')),
            'image'=>ImagesResource::make($this->whenLoaded('image')),
            'created_at'=>$this->created_at->format('Y m d, h:i A'),
        ];
    }
}
