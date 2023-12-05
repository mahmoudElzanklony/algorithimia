<?php

namespace App\Http\Controllers;

use App\Actions\ImageModalSave;
use App\Http\Controllers\Filters\NameFilter;
use App\Http\Requests\adFormRequest;
use App\Http\Requests\projectsFormRequest;
use App\Http\Resources\AdResource;
use App\Http\Resources\ProjectResource;
use App\Http\traits\messages;
use App\Http\traits\upload_image;
use App\Models\ads;
use App\Models\ads_requirments;
use App\Models\languages;
use App\Models\projects;
use App\Services\FormRequestHandleInputs;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Facades\DB;

class AdsController extends Controller
{
    //
    use upload_image;
    //
    public function index(){
        $data = ads::query()->with('requirements')->orderBy('id','DESC');
        $output = app(Pipeline::class)
            ->send($data)
            ->through([
                NameFilter::class
            ])
            ->thenReturn()
            ->get();

        return AdResource::collection($output);
    }

    public function save(adFormRequest $request){
        DB::beginTransaction();
        $data = $request->validated();
        $data =  FormRequestHandleInputs::handle_inputs_langs($data,['name','info']);

        $output = ads::query()->updateOrCreate([
            'id'=>$data['id'] ?? null
        ],$data);

        if(request()->has(app()->getLocale().'_requirements')){
            $langs = languages::query()->select('prefix')->get();
            $inputs = request(app()->getLocale().'_requirements');


            foreach($inputs as $key => $input){
                $requirements = [];
                $id = null;
                foreach($langs as $lang){
                    if($id == null){
                        $id = request('requirements_id')[$key] ?? null;
                    }
                    $requirements[$lang->prefix] = request($lang->prefix.'_requirements')[$key];
                }
                ads_requirments::query()->updateOrCreate([
                    'id'=>$id ?? null
                ],[
                    'ad_id'=>$output->id,
                    'name'=>json_encode($requirements,JSON_UNESCAPED_UNICODE),
                ]);
            }
        }
        DB::commit();
        $final = ads::query()->with('requirements')->find($output->id);
        return messages::success_output(trans('messages.saved_successfully'),AdResource::make($final));

    }
}
