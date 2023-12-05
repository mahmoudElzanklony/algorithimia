<?php


namespace App\Services\auth;


use App\Actions\ImageModalSave;

use App\Models\roles;
use App\Models\User;
use App\Http\traits\messages;

use App\Services\DB_connections;
use App\Services\mail\send_email;

class register_service
{
    use messages;
    public static function register_process($req,$validated){
        $user_info = $validated;
        // check if role exist in roles or not
        $role = roles::query()->where('name', $req['type'])->first();
        // role is correct
        if ($role != null) {
            $user_info['activation_code'] = rand(0,99999);
            $user_info['address'] = '';
            $user_info['serial_number'] = time();
            $user_info['role_id'] = $role->id;
            $user_info['password'] = bcrypt($user_info['password']);
            // create new user
            $user = User::query()->create($user_info);
            ImageModalSave::make($user->id,'User','users/default.png');



            return self::success_output(trans('messages.registered_user'),$user);
        } else {
            // role isn't correct
            return self::error_output(self::errors(['type' => trans('messages.err_invalid_type')]));
        }
    }
}
