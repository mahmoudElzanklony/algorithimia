<?php

namespace App\Http\Controllers;

use App\Actions\ImageModalSave;
use App\Http\Controllers\Filters\NameFilter;
use App\Http\Requests\categoriesFormRequest;
use App\Http\Resources\CategoryResource;
use App\Http\traits\messages;
use App\Models\categories;
use App\Models\images;
use App\Services\FormRequestHandleInputs;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use App\Http\traits\upload_image;
class CategoriesController extends Controller
{
    use upload_image;
    //
    public function index(){
        $data = categories::query()->with('image');
        $output = app(Pipeline::class)
            ->send($data)
            ->through([
                NameFilter::class
            ])
            ->thenReturn()
            ->get();

        return CategoryResource::collection($output);
    }

    public function show($id){
        $data = categories::query()->with('image')->findOrFail($id);
        return CategoryResource::make($data);
    }

    public function save(categoriesFormRequest $request){
        $data = $request->validated();
        $data =  FormRequestHandleInputs::handle_inputs_langs($data,['name']);
        $output = categories::query()->updateOrCreate([
            'id'=>$data['id'] ?? null
        ],$data);
        if(request()->hasFile('image')){
            $image = $this->upload(request()->file('image'),'categories');
            images::query()
                ->where('imageable_type','=','App\Models\categories')
                ->where('imageable_id','=',$output->id)->delete();
            ImageModalSave::make($output->id,'categories','categories/'.$image);
        }
        $data = categories::query()->with('image')->withCount(['services','projects'])->find($output->id);
        return messages::success_output(trans('messages.saved_successfully'),CategoryResource::make($data));

    }
}
