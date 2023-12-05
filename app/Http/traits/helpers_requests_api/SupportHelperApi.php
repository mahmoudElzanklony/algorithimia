<?php


namespace App\Http\traits\helpers_requests_api;


use App\Actions\ImageModalSave;
use App\Http\Controllers\Filters\EmailFilter;
use App\Http\Controllers\Filters\NameFilter;
use App\Http\Controllers\Filters\UsernameFilter;
use App\Http\Requests\categoriesFormRequest;
use App\Http\Requests\supportFormRequest;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\SupportResource;
use App\Http\traits\messages;
use App\Models\categories;
use App\Models\support;
use App\Services\FormRequestHandleInputs;
use Illuminate\Pipeline\Pipeline;

trait SupportHelperApi
{
    public function all_support(){
        $data = support::query()->orderBy('id','DESC');
        $output = app(Pipeline::class)
            ->send($data)
            ->through([
                EmailFilter::class,
                UsernameFilter::class
            ])
            ->thenReturn()
            ->get();

        return SupportResource::collection($output);
    }

    public function save_support(supportFormRequest $request){
        $data = $request->validated();
        $output = support::query()->updateOrCreate([
            'id'=>$data['id'] ?? null
        ],$data);
        return messages::success_output(trans('messages.saved_successfully'),SupportResource::make($output));

    }
}
