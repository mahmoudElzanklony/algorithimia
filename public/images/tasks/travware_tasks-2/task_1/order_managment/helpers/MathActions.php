<?php

namespace helpers;
use traits\Exceptions;
class MathActions
{
    use Exceptions;
    public  function do_operation($attributes,$actions,$key_name_changeable,$func_name)
    {
        foreach ($attributes as $key => $attribute){

            if(in_array($key,$actions)){
                try {
                    $value = $attributes[$key][$attribute][$key_name_changeable];
                    $this->$func_name($value);
                }catch (Exception $e){
                    return $this->handleError($attribute.' not exists in our system ');
                }
            }else{
                return $this->handleError($key.' not exists in our system ');
            }

        }
    }
}
