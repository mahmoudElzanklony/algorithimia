<?php

namespace App\Http\Controllers;

use App\Actions\ImageModalSave;
use App\Http\Controllers\Filters\NameFilter;
use App\Http\Requests\categoriesFormRequest;
use App\Http\Requests\servicesFormRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ServiceResource;
use App\Http\traits\messages;
use App\Http\traits\upload_image;
use App\Models\categories;
use App\Models\services;
use App\Services\FormRequestHandleInputs;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
class ServicesController extends Controller
{
    //
    use upload_image;
    //
    public function index(){
        $data = services::query()->with('image')->orderBy('id','DESC');
        $output = app(Pipeline::class)
            ->send($data)
            ->through([
                NameFilter::class
            ])
            ->thenReturn()
            ->get();

        return ServiceResource::collection($output);
    }

    public function save(servicesFormRequest $request){
        $data = $request->validated();
        $data =  FormRequestHandleInputs::handle_inputs_langs($data,['name','info']);

        $output = services::query()->updateOrCreate([
            'id'=>$data['id'] ?? null
        ],$data);
        if(request()->hasFile('image')){
            $image = $this->upload(request()->file('image'),'services');
            ImageModalSave::make($output->id,'services','services/'.$image);
        }
        return messages::success_output(trans('messages.saved_successfully'),ServiceResource::make($output));

    }
}
