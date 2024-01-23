<?php

namespace traits;


trait Exceptions
{
    public function handleError($msg){
        return throw new exception($msg);
    }
}
