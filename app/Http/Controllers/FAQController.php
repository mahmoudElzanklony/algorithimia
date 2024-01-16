<?php

namespace App\Http\Controllers;

use App\Actions\ImageModalSave;
use App\Filters\LimitFilter;
use App\Http\Controllers\Filters\NameFilter;
use App\Http\Requests\faqFormRequest;
use App\Http\Resources\AdResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\QuestionsResource;
use App\Http\traits\messages;
use App\Models\ads;
use App\Models\categories;
use App\Models\images;
use App\Models\questions;
use App\Services\FormRequestHandleInputs;
use Illuminate\Http\Request;
use Illuminate\Pipeline\Pipeline;

class FAQController extends Controller
{
    //
    public function index()
    {
        $data = questions::query()->orderBy('id','DESC');
        $output = app(Pipeline::class)
            ->send($data)
            ->through([
                NameFilter::class,
                LimitFilter::class
            ])
            ->thenReturn()
            ->get();

        return QuestionsResource::collection($output);
    }

    public function save(faqFormRequest $request)
    {
        $data = $request->validated();
        $data =  FormRequestHandleInputs::handle_inputs_langs($data,['name','answer']);
        $output = questions::query()->updateOrCreate([
            'id'=>$data['id'] ?? null
        ],$data);
        return messages::success_output(trans('messages.saved_successfully'),QuestionsResource::make($output));
    }
}
