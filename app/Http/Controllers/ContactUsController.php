<?php

namespace App\Http\Controllers;

use App\Http\traits\messages;
use App\Services\mail\send_email;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    //
    public function index()
    {
        $data = request()->only(['name','email','phone','info']);
        $data['info'] .= '  <br>Additional data phone: '.$data['phone'].' <br>Email:'.$data['email'];
        return messages::success_output(trans('messages.send_successfully'));
        //send_email::send($data['name'],$data['info'],'','','info@algo.com');
    }
}
