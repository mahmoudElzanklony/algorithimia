<?php

namespace App\Http\Controllers\Api\auth;

use App\Actions\DefaultAddress;
use App\Http\Controllers\classes\auth\AuthServicesClass;
use App\Http\Controllers\Controller;
use App\Http\Requests\usersFormRequest;
use App\Http\Resources\UserResource;
use App\Http\traits\messages;
use App\Models\cities;
use App\Models\countries;
use App\Models\roles;
use App\Models\tenants;
use App\Models\User;
use App\Services\auth\register_service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthControllerApi extends AuthServicesClass
{
    use messages;


    public function validate_user(){
        try {

            if (! $user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }

        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {

            return response()->json(['token_expired'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {

            return response()->json(['token_invalid'], $e->getStatusCode());

        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {

            return response()->json(['token_absent'], $e->getStatusCode());

        }

        return response()->json($user);
    }

    public function login_api(){
        $data = Validator::make(request()->all(),[
            'email'=>'required',
            'password'=>'required',
        ]);

        if(sizeof($data->errors()) == 0) {

            $credential = request()->only(['email', 'password']);
            $token = auth('api')->attempt($credential);
            if(!$token){
                return messages::error_output(trans('errors.unauthenticated'));
            }else {
                $user = auth('api')->user();
                $role = roles::query()->find($user->role_id);
                $user['role'] = $role;
                $user['token'] =  $token;
                return $user;
                return [
                    'user'=>$user,
                    'token'=>$token
                ];
            }
        }else{
            return messages::error_output($data->errors());
        }
    }

    public function logout_api(){
        // Get the JWT token from the request header.
        if(request()->hasHeader('token')) {
            $token = request()->header('token');
            request()->headers->set('token', (string)$token, true);
            request()->headers->set('Authorization', 'Bearer ' . $token, true);
            try {
                $token = JWTAuth::parseToken();
                $user = $token->authenticate();
                if ($user == false) {
                    return messages::error_output(['invalid credential']);
                }
            } catch (\Exception $e) {
                return messages::error_output([$e->getMessage()]);
            }
            JWTAuth::invalidate($token);
        }

        return messages::success_output('logout successfully');

    }

    public function check_otp(usersFormRequest $formRequest){
        $data = $formRequest->validated();

        $user = User::query()
            ->where('phone',$data['phone'])
            ->where('activation_code',$data['otp'])->first();
        if($user != null){
            $user->activation_status = 1;
            $user->save();
            return messages::success_output(trans('messages.activation_done'),$user);
        }else{
            return messages::error_output(trans('errors.incorrect_otp'),401);
        }
    }

    public function user(){
        return auth()->user();
    }

}
