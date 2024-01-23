<?php

class User
{
    private $name;
    private $address;
    private $age;
    private $gender;
    private $image;

    public function __construct($name ,  Address $address)
    {
        $this->name = $name;
        $this->address = $address;
    }

    public function get_name()
    {
        return $this->name;
    }
    public function get_address()
    {
        return $this->address;
    }


    public function notify($message){
        /**
         * TODO
         * we need to add channel to send notification to user but not now
         */
    }
}
