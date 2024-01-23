<?php

class Address
{
    private $city;
    private $street;
    private $country;
    private $nearest_point;
    private $map;


    public function __construct($city , $street , $country , $nearest_point = null , $map = null)
    {
        $this->city = $city;
        $this->street = $street;
        $this->country = $country;
        $this->nearest_point = $nearest_point;
        $this->map = $map;
    }
    public function get_city()
    {
        return $this->city;
    }

    public function get_street()
    {
        return $this->street;
    }

    public function get_country()
    {
        return $this->country;
    }

    public function get_nearest_point()
    {
        return $this->nearest_point;
    }

    public function get_map()
    {
        return $this->map;
    }
}
