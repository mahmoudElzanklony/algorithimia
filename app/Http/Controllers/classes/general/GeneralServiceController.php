<?php

namespace App\Http\Controllers\classes\general;

use App\Http\Controllers\Controller;
use App\Http\traits\messages;
use App\Models\advertising_points_price;
use App\Models\images;
use App\Models\listings_info;
use App\Models\products_care;
use App\Services\notifications\pagiante_notifications;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GeneralServiceController extends Controller
{
    //
    public function delete_item(){
        $table = request('table');
        try {
            $model = app("App\Models\\".$table);
            $model->where('id',request('id'))->delete();
        }catch (\Throwable $e){
            if(request()->has('id')) {
                $id = request('id');
                DB::table($table)->delete($id);
            }
        }
        $images_found_check = images::query()
            ->where('imageable_id','=',request('id'))
            ->where('imageable_type','=','App\Models\\'.$table)->delete();
        return messages::success_output(trans('messages.deleted_successfully'));

    }


    public function paginate_notification_data(){
        $id = request('id') ?? 0;
        $type = request('type') ?? '';
        return pagiante_notifications::get_notifications($id,$type);
    }

    public function get_map_data_type(){
        $model =  '\\App\\Models\\'.request('type');
        $model = new $model();
        $data = $model->select('id',app()->getLocale().'_name as name')->get();
        return response()->json($data);

    }

    public function get_next_map_type(){
        $model =  '\\App\\Models\\'.request('type');
        $model = new $model();
        $data = $model->select('id',app()->getLocale().'_name as name')
            ->where(request('whereColumn'),'=',request('id'))->get();
        return response()->json($data);
    }
}
