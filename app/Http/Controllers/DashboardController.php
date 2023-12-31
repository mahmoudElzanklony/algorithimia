<?php

namespace App\Http\Controllers;

use App\Filters\EndDateFilter;
use App\Filters\StartDateFilter;
use App\Filters\UsernameFilter;
use App\Filters\users\RoleIdFilter;
use App\Filters\users\RoleNameFilter;
use App\Http\Resources\UserResource;
use App\Http\traits\messages;
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



}
