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
            'name'=>FormRequestHandleInputs::handle_output_column($this->name),
            'info'=>FormRequestHandleInputs::handle_output_column($this->info),
            'requirements'=>AdRequirmentResource::collection($this->whenLoaded('requirements')),
            'created_at'=>$this->created_at->format('Y m d, h:i A'),
        ];
    }
}
