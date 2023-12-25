<?php


namespace App\Filters\projects;
use Closure;

class CategoryProjectFilter
{
    public function handle($request, Closure $next){
        if(request()->has('category_id')){
            return $next($request)
                ->whereHas('service',function($s){
                    $s->whereHas('category',function($c){
                        $c->where('id','=',request('category_id'));
                    });
                });
        }
        return $next($request);
    }
}
