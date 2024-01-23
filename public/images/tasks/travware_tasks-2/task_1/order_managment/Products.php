<?php

use interfaces\RequiredActions;
use traits\Exceptions;
use helpers\MathActions;
class Products implements RequiredActions
{
    use Exceptions;
    private $name;
    private $quantity;
    private $category;
    private $attributes;
    private $price;

    public function __construct($name,$quantity,$category,$attributes,$price)
    {
        $this->name = $name;
        $this->quantity = $quantity;
        $this->category = $category;
        $this->attributes = $attributes;
        $this->price = $price;
    }


    public function change_price($price){
        $this->price += $price;
    }
    public function get_name(){
        return $this->name;
    }

    public function get_quantity(){
        return $this->quantity;
    }

    public function get_category(){
        return $this->category;
    }

    public function get_attributes(){
        return $this->attributes;
    }

    public function get_price(){
        return $this->price;
    }

    public function calculate_price(){
        $helper = new MathActions();
        $helper->do_operation($this->get_attributes(),$this->make_actions(),'price','change_price');
        foreach ($this->get_attributes() as $key => $attribute){

            /*if(in_array($key,$this->make_actions())){
                try {
                    $value = $this->get_attributes()[$key][$attribute]['price'];
                    $this->change_price($value);
                }catch (Exception $e){
                    return $this->handleError($attribute.' not exists in our system ');
                }
            }else{
                return $this->handleError($key.' not exists in our system ');
            }*/
            /*if ($key == 'size'){
                if ($attribute == 'small') {
                    $this->_price -= 10;
                }
                elseif ($attribute == 'medium') {
                    $this->_price += 20;
                }
                elseif ($attribute == 'large') {
                    $this->_price += 50;
                }
                else {
                    throw new exception("error in size");
                }
            }
            elseif ($key == 'color'){
                if ($attribute == 'white') {
                    $this->_price -= 15;
                }
                elseif ($attribute == 'red') {
                    $this->_price += 20;
                }
                elseif ($attribute == 'blue') {
                    $this->_price += 18;
                }
                else {
                    throw new exception("error in color");
                }
            }
            else {
                throw new exception("error in attribute");
            }*/
        }
        return $this->get_price();
    }

    public function make_actions()
    {
        // actions data and this should be stored at table database
        // if you ask why you dont make small => -10 rather than array
        // because if you change any key or add any key it will be inside array
        $actions = [
            'size'=>[
                'small'=>['price'=>-10],
                'medium'=>['price'=>20],
                'large'=>['price'=>50],
            ],
            'color'=>[
                'white'=>['price'=>-50],
                'medium'=>['price'=>20],
                'large'=>['price'=>18],
            ],
        ];
        return $actions;
    }


}
