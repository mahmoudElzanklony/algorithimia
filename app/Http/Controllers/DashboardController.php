<?php

namespace App\Http\Controllers;

use App\Filters\EndDateFilter;
use App\Filters\LimitFilter;
use App\Filters\ServiceIdFilter;
use App\Filters\StartDateFilter;
use App\Filters\UsernameFilter;
use App\Filters\users\RoleIdFilter;
use App\Filters\users\RoleNameFilter;
use App\Http\Controllers\Filters\NameFilter;
use App\Http\Resources\AdResource;
use App\Http\Resources\CategoryResource;
use App\Http\Resources\ProjectResource;
use App\Http\Resources\QuestionsResource;
use App\Http\Resources\ServiceResource;
use App\Http\Resources\UserResource;
use App\Http\traits\messages;
use App\Models\ads;
use App\Models\categories;
use App\Models\projects;
use App\Models\questions;
use App\Models\services;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\traits\helpers_requests_api\TicketsHelperApi;
use App\Http\traits\helpers_requests_api\SupportHelperApi;

use Illuminate\Pipeline\Pipeline;

class DashboardController extends Controller
{
    //
    use TicketsHelperApi,SupportHelperApi;

    public function get_users(){
         $users = User::query()->with('role')
             ->when(request()->filled('role_name') && (request('role_name') == 'client' || request('role_name') == 'company'),function($e){
                 $e->withCount('orders');
             })
             ->when(request()->filled('role_name') && request('role_name') == 'seller' ,function($e){
                 $e->withCount('articles');
             })
             ->orderBy('id','DESC')->when(request()->filled('role_name') && request('role_name'),function($e){
                 $e->withCount('products')->withCount('articles');
             });

        $output = app(Pipeline::class)
            ->send($users)
            ->through([
                StartDateFilter::class,
                EndDateFilter::class,
                UsernameFilter::class,
                RoleNameFilter::class
            ])
            ->thenReturn()
            ->paginate(15);
        return UserResource::collection($output);

    }

    public function statistics()
    {
        return messages::success_output('',[
            'categories'=>categories::query()->count(),
            'services'=>services::query()->count(),
            'projects'=>services::query()->count(),
            'posts'=>ads::query()->count(),
            'FAQs'=>questions::query()->count(),
        ]);
    }

    public function categories()
    {
        $data = categories::query()->with('image')->withCount(['services','projects'])->get();
        return CategoryResource::collection($data);
    }

    public function services()
    {
        $data = services::query()->with(['images','category'])->withCount(['projects'])->get();
        return ServiceResource::collection($data);
    }

    public function projects()
    {
        $data = projects::query()->with(['images','service'])->orderBy('id','DESC');
        $output = app(Pipeline::class)
            ->send($data)
            ->through([
                NameFilter::class,
                ServiceIdFilter::class,
                LimitFilter::class
            ])
            ->thenReturn()
            ->get();
        return ProjectResource::collection($output);
    }

    public function posts()
    {
        $data = ads::query()->with(['image','requirements'])->orderBy('id','DESC');
        $output = app(Pipeline::class)
            ->send($data)
            ->through([
                NameFilter::class,
                LimitFilter::class
            ])
            ->thenReturn()
            ->get();
        return AdResource::collection($output);
    }

    public function faqs()
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

}
