<?php

namespace App\Http\Resources;

use App\Services\FormRequestHandleInputs;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionsResource extends JsonResource
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
          'answer'=>str_contains(request()->fullUrl(), 'dashboard') == false ? FormRequestHandleInputs::handle_output_column($this->answer):$this->answer,
          'created_at'=>$this->created_at->format('Y m d, h:i A'),
        ];
    }
}
