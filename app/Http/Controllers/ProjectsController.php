<?php

namespace App\Http\Controllers;

use App\Actions\ImageModalSave;
use App\Filters\LimitFilter;
use App\Filters\projects\CategoryProjectFilter;
use App\Filters\ServiceIdFilter;
use App\Http\Controllers\Filters\NameFilter;
use App\Http\Requests\categoriesFormRequest;
use App\Http\Requests\projectsFormRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProjectResource;
use App\Http\traits\messages;
use App\Http\traits\upload_image;
use App\Models\categories;
use App\Models\projects;
use App\Services\FormRequestHandleInputs;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class ProjectsController extends Controller
{
    //
    use upload_image;
    //
    public function index(){
        $data = projects::query()->with(['images','service.category'])

            ->orderBy('id','DESC');
        $output = app(Pipeline::class)
            ->send($data)
            ->through([
                NameFilter::class,
                CategoryProjectFilter::class,
                LimitFilter::class,
                ServiceIdFilter::class
            ])
            ->thenReturn()
            ->get();

        return ProjectResource::collection($output);
    }

    public function save(projectsFormRequest $request){
        DB::beginTransaction();
        $data = $request->validated();
        $data =  FormRequestHandleInputs::handle_inputs_langs($data,['name','info']);

        $output = projects::query()->updateOrCreate([
            'id'=>$data['id'] ?? null
        ],$data);
        if(request()->hasFile('images')){
            foreach(request()->file('images') as $file){
                $img = $this->upload($file,'projects');
                ImageModalSave::make($output->id,'projects','projects/'.$img);
            }
        }
        DB::commit();
        $result  = projects::query()->with(['images','service'])->find($output->id);
        return messages::success_output(trans('messages.saved_successfully'),ProjectResource::make($result));

    }
}
